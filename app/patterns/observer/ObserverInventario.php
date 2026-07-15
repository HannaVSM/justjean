<?php

/**
 * Patrón Observer
 * Notifica a los suscriptores cuando ocurre un evento relevante (ej: stock bajo).
 * Diagrama: ListaClientes (Observer) → NotificadorUI | NotificadorEmail | LoggerEventos
 *           Clientes (Subject) suscribir/desuscribir/notificar
 */

// ─── Interfaz Observer ────────────────────────────────────────────────────────

interface ListaClientes {
    public function actualizar(string $evento, $datos): void;
}

// ─── Observadores concretos ───────────────────────────────────────────────────

/**
 * Almacena las notificaciones en sesión para mostrarlas en la UI.
 */
class NotificadorUI implements ListaClientes {
    public function actualizar(string $evento, $datos): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['notificaciones'])) {
            $_SESSION['notificaciones'] = [];
        }
        $_SESSION['notificaciones'][] = [
            'evento' => $evento,
            'datos'  => $datos,
            'hora'   => date('H:i:s'),
        ];
    }
}

/**
 * Simula el envío de un correo electrónico al administrador.
 * En producción se conectaría con PHPMailer u otro servicio.
 */
class NotificadorEmail implements ListaClientes {
    private string $destinatario;

    public function __construct(string $destinatario = 'admin@creaciones-justean.com') {
        $this->destinatario = $destinatario;
    }

    public function actualizar(string $evento, $datos): void {
        // Registrar en log que se "envió" el correo (sin dependencia externa)
        $mensaje = "[EMAIL] [{$evento}] Para: {$this->destinatario} | Datos: " . json_encode($datos);
        error_log($mensaje);
    }
}

/**
 * Escribe los eventos en el log de PHP.
 */
class LoggerEventos implements ListaClientes {
    public function actualizar(string $evento, $datos): void {
        $entrada = date('Y-m-d H:i:s') . " | [{$evento}] " . json_encode($datos);
        error_log($entrada);
    }
}

// ─── Sujeto (Subject) ─────────────────────────────────────────────────────────

class GestorEventosInventario {
    /** @var ListaClientes[] */
    private array $suscriptores = [];

    public function suscribir(ListaClientes $observador): void {
        $this->suscriptores[] = $observador;
    }

    public function desuscribir(ListaClientes $observador): void {
        $this->suscriptores = array_filter(
            $this->suscriptores,
            fn($s) => $s !== $observador
        );
    }

    public function notificar(string $evento, $datos): void {
        foreach ($this->suscriptores as $suscriptor) {
            $suscriptor->actualizar($evento, $datos);
        }
    }

    // ── Métodos de dominio que disparan eventos ───────────────────────────────

    /**
     * Llama a esto después de actualizar el stock de un insumo.
     * Si queda bajo el mínimo, notifica a todos los suscriptores.
     */
    public function verificarAlertaStock(object $insumo): void {
        if (
            isset($insumo->stockActual, $insumo->stockMinimo) &&
            $insumo->stockActual < $insumo->stockMinimo
        ) {
            $this->notificar('STOCK_BAJO', [
                'idInsumo'     => $insumo->idInsumo   ?? null,
                'nombre'       => $insumo->nombre      ?? 'Desconocido',
                'stockActual'  => $insumo->stockActual,
                'stockMinimo'  => $insumo->stockMinimo,
            ]);
        }
    }

    /**
     * Notifica cuando se registra un nuevo pedido.
     */
    public function notificarNuevoPedido(array $datosPedido): void {
        $this->notificar('NUEVO_PEDIDO', $datosPedido);
    }
}

// ─── Fábrica del gestor preconfigurado ────────────────────────────────────────

class GestorEventosFactory {
    /**
     * Devuelve un GestorEventosInventario listo con UI + Logger suscritos.
     */
    public static function crear(): GestorEventosInventario {
        $gestor = new GestorEventosInventario();
        $gestor->suscribir(new NotificadorUI());
        $gestor->suscribir(new LoggerEventos());
        return $gestor;
    }
}
