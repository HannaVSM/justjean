<?php

/**
 * Patrón Command
 * Encapsula las operaciones de inventario como objetos ejecutables y deshacibles.
 * Diagrama: Comando → RegistrarEntradaComando | RegistrarSalidaComando | GenerarReporteComando
 *           Invocador (historial con stack)
 */

// ─── Interfaz de comando ──────────────────────────────────────────────────────

interface Comando {
    public function ejecutar(): bool;
    public function deshacer(): bool;
}

// ─── Comandos concretos ───────────────────────────────────────────────────────

/**
 * Registra una entrada de insumo y puede deshacerla restando la cantidad.
 */
class RegistrarEntradaComando implements Comando {
    private object $datos;       // stdClass con idInsumo, cantidad, etc.
    private object $servicioInsumo;
    private object $servicioMovimiento;
    private bool   $ejecutado = false;

    public function __construct(object $datos, object $servicioInsumo, object $servicioMovimiento) {
        $this->datos              = $datos;
        $this->servicioInsumo     = $servicioInsumo;
        $this->servicioMovimiento = $servicioMovimiento;
    }

    public function ejecutar(): bool {
        $ok = $this->servicioInsumo->actualizarStock(
            $this->datos->idInsumo,
            $this->datos->cantidad
        );
        if ($ok) {
            $this->servicioMovimiento->registrar((array)$this->datos);
            $this->ejecutado = true;
        }
        return $ok;
    }

    public function deshacer(): bool {
        if (!$this->ejecutado) {
            return false;
        }
        // Revertir: restar la misma cantidad que se sumó
        return $this->servicioInsumo->actualizarStock(
            $this->datos->idInsumo,
            -$this->datos->cantidad
        );
    }
}

/**
 * Registra una salida de insumo verificando disponibilidad, y puede deshacerla.
 */
class RegistrarSalidaComando implements Comando {
    private object $datos;
    private object $servicioInsumo;
    private object $servicioMovimiento;
    private bool   $ejecutado = false;

    public function __construct(object $datos, object $servicioInsumo, object $servicioMovimiento) {
        $this->datos              = $datos;
        $this->servicioInsumo     = $servicioInsumo;
        $this->servicioMovimiento = $servicioMovimiento;
    }

    public function ejecutar(): bool {
        // La verificación de disponibilidad ya fue realizada por el patrón Strategy
        // en el controller antes de crear este comando. No se repite aquí.
        $ok = $this->servicioInsumo->actualizarStock(
            $this->datos->idInsumo,
            -$this->datos->cantidad
        );
        if ($ok) {
            $this->servicioMovimiento->registrar((array)$this->datos);
            $this->ejecutado = true;
        }
        return $ok;
    }

    public function deshacer(): bool {
        if (!$this->ejecutado) {
            return false;
        }
        // Revertir: devolver la cantidad que se descontó
        return $this->servicioInsumo->actualizarStock(
            $this->datos->idInsumo,
            $this->datos->cantidad
        );
    }
}

/**
 * Genera un reporte; el "deshacer" limpia el resultado almacenado.
 */
class GenerarReporteComando implements Comando {
    private string $tipo;
    private object $servicioInsumo;
    private ?string $resultado = null;

    public function __construct(string $tipo, object $servicioInsumo) {
        $this->tipo           = $tipo;
        $this->servicioInsumo = $servicioInsumo;
    }

    public function ejecutar(): bool {
        $datos = $this->servicioInsumo->obtenerTodos();
        if ($datos === false) {
            return false;
        }
        require_once __DIR__ . '/../decorator/ReporteDecorador.php';
        $base = new ReporteBase(is_array($datos) ? $datos : []);

        $this->resultado = match ($this->tipo) {
            'pdf'   => (new ReportePDF($base))->generarReporte(),
            'excel' => (new ReporteExcel($base))->generarReporte(),
            default => $base->generarReporte(),
        };
        return true;
    }

    public function deshacer(): bool {
        $this->resultado = null;
        return true;
    }

    public function getResultado(): ?string {
        return $this->resultado;
    }
}

// ─── Invocador ────────────────────────────────────────────────────────────────

/**
 * Mantiene un historial de comandos ejecutados y permite deshacer el último.
 */
class Invocador {
    /** @var Comando[] */
    private array $historial = [];
    private ?Comando $comandoActual = null;

    public function setComando(Comando $comando): void {
        $this->comandoActual = $comando;
    }

    public function ejecutarComando(): bool {
        if ($this->comandoActual === null) {
            return false;
        }
        $ok = $this->comandoActual->ejecutar();
        if ($ok) {
            $this->historial[] = $this->comandoActual;
        }
        return $ok;
    }

    public function deshacer(): bool {
        if (empty($this->historial)) {
            return false;
        }
        $ultimo = array_pop($this->historial);
        return $ultimo->deshacer();
    }

    public function contarHistorial(): int {
        return count($this->historial);
    }
}
