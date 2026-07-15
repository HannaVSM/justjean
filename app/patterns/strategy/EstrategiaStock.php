<?php

/**
 * Patrón Strategy
 * Permite intercambiar el algoritmo de cálculo de stock en tiempo de ejecución.
 * Diagrama: EstrategiaStock → EstrategiaFIFO | EstrategiaLIFO | EstrategiaPromedio
 */

// ─── Interfaz de estrategia ───────────────────────────────────────────────────

interface EstrategiaStock {
    /**
     * Calcula el stock disponible real.
     * @param float $disponible Stock físico en almacén.
     * @param float $reservado  Stock ya reservado para pedidos activos.
     * @return float Stock calculado según la estrategia.
     */
    public function calcularStock(float $disponible, float $reservado): float;
}

// ─── Estrategias concretas ────────────────────────────────────────────────────

/**
 * FIFO: el stock disponible descuenta el reservado directamente.
 * Los primeros insumos en entrar son los primeros en salir.
 */
class EstrategiaFIFO implements EstrategiaStock {
    public function calcularStock(float $disponible, float $reservado): float {
        return max(0.0, $disponible - $reservado);
    }
}

/**
 * LIFO: igual que FIFO en términos de cantidad, pero conceptualmente
 * los últimos en entrar son los primeros en salir.
 * Se mantiene la misma fórmula; el orden de salida es un detalle de lote.
 */
class EstrategiaLIFO implements EstrategiaStock {
    public function calcularStock(float $disponible, float $reservado): float {
        return max(0.0, $disponible - $reservado);
    }
}

/**
 * Promedio ponderado: el stock disponible se calcula como el promedio
 * entre el stock físico y lo que aún no se ha reservado.
 * Útil para reportes de valorización.
 */
class EstrategiaPromedio implements EstrategiaStock {
    public function calcularStock(float $disponible, float $reservado): float {
        if ($disponible <= 0) return 0.0;
        $libre = max(0.0, $disponible - $reservado);
        return ($disponible + $libre) / 2;
    }
}

// ─── Contexto de estrategia ───────────────────────────────────────────────────

class CalculadorStock {
    private EstrategiaStock $estrategia;

    public function __construct(?EstrategiaStock $estrategia = null) {
        $this->estrategia = $estrategia ?? new EstrategiaFIFO();
    }

    public function setEstrategia(EstrategiaStock $estrategia): void {
        $this->estrategia = $estrategia;
    }

    public function obtenerStock(float $disponible, float $reservado): float {
        return $this->estrategia->calcularStock($disponible, $reservado);
    }

    /**
     * Shortcut para crear el calculador con una estrategia por nombre.
     */
    public static function conEstrategia(string $nombre): self {
        $estrategia = match (strtolower($nombre)) {
            'lifo'    => new EstrategiaLIFO(),
            'promedio'=> new EstrategiaPromedio(),
            default   => new EstrategiaFIFO(),
        };
        return new self($estrategia);
    }
}
