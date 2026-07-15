<?php

/**
 * Patrón Prototype
 * Permite clonar pedidos existentes para crear nuevos sin repetir los datos.
 * Diagrama: PedidoInterfaz → PedidoConcreto | DuplicarPedido
 */

// ─── Interfaz Prototype ───────────────────────────────────────────────────────

interface PedidoInterfaz {
    public function clonar(): self;
}

// ─── Pedido concreto clonable ─────────────────────────────────────────────────

class PedidoConcreto implements PedidoInterfaz {
    public int    $id;
    public string $fecha;
    public string $cliente;
    public string $estado;
    /** @var array Items/prendas del pedido */
    public array  $items;

    public function __construct(
        int    $id      = 0,
        string $fecha   = '',
        string $cliente = '',
        string $estado  = 'Registrado',
        array  $items   = []
    ) {
        $this->id      = $id;
        $this->fecha   = $fecha;
        $this->cliente = $cliente;
        $this->estado  = $estado;
        $this->items   = $items;
    }

    /**
     * Crea una copia profunda del pedido.
     * El ID se resetea a 0 (es un nuevo pedido) y la fecha se actualiza.
     */
    public function clonar(): self {
        $clon          = clone $this;
        $clon->id      = 0;                        // Nuevo pedido: sin ID todavía
        $clon->fecha   = date('Y-m-d');            // Fecha actual
        $clon->estado  = 'Registrado';             // Estado inicial
        $clon->items   = $this->items;             // Copia de items (array simple)
        return $clon;
    }

    /**
     * Convierte el pedido a array para persistirlo en BD.
     */
    public function toArray(): array {
        return [
            'cliente' => $this->cliente,
            'fecha'   => $this->fecha,
            'estado'  => $this->estado,
        ];
    }

    /**
     * Crea un PedidoConcreto desde un objeto de BD.
     */
    public static function desdeDB(object $row): self {
        return new self(
            (int)($row->idPedido ?? 0),
            $row->fecha   ?? date('Y-m-d'),
            $row->cliente ?? '',
            $row->estado  ?? 'Registrado',
            []
        );
    }
}

// ─── Servicio de duplicación ──────────────────────────────────────────────────

class DuplicarPedido {
    /**
     * Clona el pedido dado y opcionalmente cambia el cliente.
     */
    public function duplicarPedido(PedidoConcreto $original, string $nuevoCliente = ''): PedidoConcreto {
        $clon = $original->clonar();
        if ($nuevoCliente !== '') {
            $clon->cliente = $nuevoCliente;
        }
        return $clon;
    }
}
