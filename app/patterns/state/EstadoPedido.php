<?php

/**
 * Patrón State
 * Cada estado del pedido encapsula su propio comportamiento y las transiciones válidas.
 * Diagrama: EstadoPedido → EstadoEspera | EstadoProceso | EstadoFinalizado
 *
 * Estados mapeados a los valores reales de la BD:
 *   'Registrado'    → EstadoEspera
 *   'En Produccion' → EstadoProceso
 *   'Finalizado'    → EstadoFinalizado
 *   'Cancelado'     → EstadoCancelado
 */

// ─── Interfaz de estado ───────────────────────────────────────────────────────

interface EstadoPedidoInterface {
    public function aprobar(PedidoContexto $pedido): void;
    public function iniciar(PedidoContexto $pedido): void;
    public function finalizar(PedidoContexto $pedido): void;
    public function cancelar(PedidoContexto $pedido): void;
    public function getNombre(): string;
}

// ─── Contexto ─────────────────────────────────────────────────────────────────

/**
 * Mantiene el estado actual y delega las transiciones al objeto de estado.
 * Se integra con el modelo Pedido (BD) a través del callback $persistir.
 */
class PedidoContexto {
    private EstadoPedidoInterface $estado;
    private int $idPedido;
    /** @var callable|null Función para persistir el nuevo estado en BD */
    private $persistir;

    public function __construct(int $idPedido, string $estadoActual, ?callable $persistir = null) {
        $this->idPedido  = $idPedido;
        $this->persistir = $persistir;
        $this->estado    = EstadoPedidoFactory::desde($estadoActual);
    }

    public function setEstado(EstadoPedidoInterface $nuevoEstado): void {
        $this->estado = $nuevoEstado;
        if ($this->persistir) {
            ($this->persistir)($this->idPedido, $nuevoEstado->getNombre());
        }
    }

    public function getEstado(): EstadoPedidoInterface {
        return $this->estado;
    }

    public function getNombreEstado(): string {
        return $this->estado->getNombre();
    }

    // Delegación de acciones al estado actual
    public function aprobar(): void   { $this->estado->aprobar($this); }
    public function iniciar(): void   { $this->estado->iniciar($this); }
    public function finalizar(): void { $this->estado->finalizar($this); }
    public function cancelar(): void  { $this->estado->cancelar($this); }
}

// ─── Estados concretos ────────────────────────────────────────────────────────

class EstadoEspera implements EstadoPedidoInterface {
    public function aprobar(PedidoContexto $pedido): void {
        $pedido->setEstado(new EstadoProceso());
    }
    public function iniciar(PedidoContexto $pedido): void {
        // No se puede iniciar sin aprobar primero
    }
    public function finalizar(PedidoContexto $pedido): void {
        // No se puede finalizar desde espera
    }
    public function cancelar(PedidoContexto $pedido): void {
        $pedido->setEstado(new EstadoCancelado());
    }
    public function getNombre(): string { return 'Registrado'; }
}

class EstadoProceso implements EstadoPedidoInterface {
    public function aprobar(PedidoContexto $pedido): void {
        // Ya está en proceso
    }
    public function iniciar(PedidoContexto $pedido): void {
        // Ya iniciado
    }
    public function finalizar(PedidoContexto $pedido): void {
        $pedido->setEstado(new EstadoFinalizado());
    }
    public function cancelar(PedidoContexto $pedido): void {
        $pedido->setEstado(new EstadoCancelado());
    }
    public function getNombre(): string { return 'En Produccion'; }
}

class EstadoFinalizado implements EstadoPedidoInterface {
    public function aprobar(PedidoContexto $pedido): void  { /* No aplica */ }
    public function iniciar(PedidoContexto $pedido): void  { /* No aplica */ }
    public function finalizar(PedidoContexto $pedido): void { /* Ya finalizado */ }
    public function cancelar(PedidoContexto $pedido): void  { /* No se puede cancelar */ }
    public function getNombre(): string { return 'Finalizado'; }
}

class EstadoCancelado implements EstadoPedidoInterface {
    public function aprobar(PedidoContexto $pedido): void  { /* No aplica */ }
    public function iniciar(PedidoContexto $pedido): void  { /* No aplica */ }
    public function finalizar(PedidoContexto $pedido): void { /* No aplica */ }
    public function cancelar(PedidoContexto $pedido): void  { /* Ya cancelado */ }
    public function getNombre(): string { return 'Cancelado'; }
}

// ─── Fábrica de estados ───────────────────────────────────────────────────────

class EstadoPedidoFactory {
    public static function desde(string $nombreEstado): EstadoPedidoInterface {
        return match ($nombreEstado) {
            'Registrado'    => new EstadoEspera(),
            'En Produccion' => new EstadoProceso(),
            'Finalizado'    => new EstadoFinalizado(),
            'Cancelado'     => new EstadoCancelado(),
            default         => new EstadoEspera(),
        };
    }
}
