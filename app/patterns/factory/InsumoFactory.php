<?php

/**
 * Patrón Abstract Factory
 * Permite crear distintos tipos de Insumo sin acoplar el código al tipo concreto.
 * Diagrama: InsumoFactory → TelaFactory | HiloFactory | TelaAntifluidoFactory
 */

// ─── Producto base ────────────────────────────────────────────────────────────

class InsumoProducto {
    public string $nombre;
    public string $unidad;
    public float  $stockActual;
    public float  $stockMinimo;
    public string $tipo;
    public string $descripcion;
    public float  $costo;

    public function __construct(array $datos) {
        $this->nombre      = $datos['nombre']      ?? '';
        $this->unidad      = $datos['unidadMedida'] ?? '';
        $this->stockActual = (float)($datos['stockActual'] ?? 0);
        $this->stockMinimo = (float)($datos['stockMinimo'] ?? 0);
        $this->descripcion = $datos['descripcion'] ?? '';
        $this->costo       = (float)($datos['costo'] ?? 0);
        $this->tipo        = $datos['tipo'] ?? '';
    }

    public function obtenerTipo(): string {
        return $this->tipo;
    }

    public function actualizarStock(float $cantidad): void {
        $this->stockActual += $cantidad;
    }

    public function estaBajoMinimo(): bool {
        return $this->stockActual < $this->stockMinimo;
    }
}

class Tela extends InsumoProducto {
    public function obtenerTipo(): string { return 'Tela'; }
}

class Hilo extends InsumoProducto {
    public function obtenerTipo(): string { return 'Hilo'; }
}

class TelaAntifluido extends InsumoProducto {
    public function obtenerTipo(): string { return 'TelaAntifluido'; }
}

// ─── Interfaz de fábrica ──────────────────────────────────────────────────────

interface InsumoFactoryInterface {
    public function crearInsumo(array $datos): InsumoProducto;
}

// ─── Fábricas concretas ───────────────────────────────────────────────────────

class TelaFactory implements InsumoFactoryInterface {
    public function crearInsumo(array $datos): InsumoProducto {
        $datos['tipo'] = 'Tela';
        return new Tela($datos);
    }
}

class HiloFactory implements InsumoFactoryInterface {
    public function crearInsumo(array $datos): InsumoProducto {
        $datos['tipo'] = 'Hilo';
        return new Hilo($datos);
    }
}

class TelaAntifluidoFactory implements InsumoFactoryInterface {
    public function crearInsumo(array $datos): InsumoProducto {
        $datos['tipo'] = 'TelaAntifluido';
        return new TelaAntifluido($datos);
    }
}

// ─── Selector de fábrica ──────────────────────────────────────────────────────

class InsumoFactorySelector {
    /**
     * Devuelve la fábrica correspondiente al tipo indicado.
     * Uso: InsumoFactorySelector::obtener('Tela')->crearInsumo($datos)
     */
    public static function obtener(string $tipo): InsumoFactoryInterface {
        return match (strtolower($tipo)) {
            'tela'           => new TelaFactory(),
            'hilo'           => new HiloFactory(),
            'telaantifluido' => new TelaAntifluidoFactory(),
            default          => new TelaFactory(),
        };
    }
}
