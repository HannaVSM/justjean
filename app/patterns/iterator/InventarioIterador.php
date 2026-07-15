<?php

/**
 * Patrón Iterator
 * Permite recorrer la colección de insumos sin exponer su estructura interna.
 * Diagrama: Agregador → InventarioAgregado | Iterador → InsumoIterador
 */

// ─── Interfaces ───────────────────────────────────────────────────────────────

interface IteradorInsumos {
    public function getNext(): ?object;
    public function hasMore(): bool;
    public function reset(): void;
}

interface AgregadorInsumos {
    public function crearIterador(): IteradorInsumos;
}

// ─── Iterador concreto ────────────────────────────────────────────────────────

class InsumoIterador implements IteradorInsumos {
    private int   $posicion = 0;
    private array $lista;

    public function __construct(array $lista) {
        $this->lista = $lista;
    }

    public function hasMore(): bool {
        return $this->posicion < count($this->lista);
    }

    public function getNext(): ?object {
        if (!$this->hasMore()) {
            return null;
        }
        $item = $this->lista[$this->posicion];
        $this->posicion++;
        return is_object($item) ? $item : (object)$item;
    }

    public function reset(): void {
        $this->posicion = 0;
    }
}

// ─── Agregado concreto ────────────────────────────────────────────────────────

class InventarioAgregado implements AgregadorInsumos {
    private array $insumos = [];

    public function __construct(array $insumos = []) {
        $this->insumos = $insumos;
    }

    public function agregar(object $insumo): void {
        $this->insumos[] = $insumo;
    }

    public function crearIterador(): IteradorInsumos {
        return new InsumoIterador($this->insumos);
    }

    /**
     * Retorna solo los insumos que están bajo su stock mínimo.
     * Útil para el módulo de alertas del dashboard.
     */
    public function iteradorBajoStock(): IteradorInsumos {
        $filtrados = array_filter($this->insumos, function ($i) {
            $item = is_object($i) ? $i : (object)$i;
            return isset($item->stockActual, $item->stockMinimo)
                && $item->stockActual < $item->stockMinimo;
        });
        return new InsumoIterador(array_values($filtrados));
    }
}
