<?php

/**
 * Patrón Bridge
 * Desacopla la abstracción (Repositorio) de su implementación (MySQL, Mock).
 * Diagrama: Repositorio → ImplementacionRepositorio → MySQLInventario | MockInventario
 */

// ─── Interfaz de implementación ───────────────────────────────────────────────

interface ImplementacionRepositorio {
    public function guardar(array $entidad): bool;
    public function actualizar(array $entidad): bool;
    public function eliminar(int $id): bool;
    public function buscar(int $id): ?object;
    public function listar(): array;
}

// ─── Implementación MySQL ─────────────────────────────────────────────────────

/**
 * Implementación real que usa la clase Database existente del proyecto.
 * La tabla y los campos se configuran al instanciar.
 */
class MySQLInventario implements ImplementacionRepositorio {
    private object $db;
    private string $tabla;
    private string $pkCampo;

    public function __construct(object $db, string $tabla, string $pkCampo) {
        $this->db      = $db;
        $this->tabla   = $tabla;
        $this->pkCampo = $pkCampo;
    }

    public function guardar(array $entidad): bool {
        $campos = array_keys($entidad);
        $placeholders = array_map(fn($c) => ":$c", $campos);
        $sql = "INSERT INTO {$this->tabla} (" . implode(',', $campos) . ") "
             . "VALUES (" . implode(',', $placeholders) . ")";
        $this->db->query($sql);
        foreach ($entidad as $campo => $valor) {
            $this->db->bind(":$campo", $valor);
        }
        return $this->db->execute();
    }

    public function actualizar(array $entidad): bool {
        $pk = $this->pkCampo;
        $sets = implode(', ', array_map(
            fn($c) => "$c = :$c",
            array_filter(array_keys($entidad), fn($c) => $c !== $pk)
        ));
        $sql = "UPDATE {$this->tabla} SET $sets WHERE $pk = :$pk";
        $this->db->query($sql);
        foreach ($entidad as $campo => $valor) {
            $this->db->bind(":$campo", $valor);
        }
        return $this->db->execute();
    }

    public function eliminar(int $id): bool {
        $this->db->query("DELETE FROM {$this->tabla} WHERE {$this->pkCampo} = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function buscar(int $id): ?object {
        $this->db->query("SELECT * FROM {$this->tabla} WHERE {$this->pkCampo} = :id");
        $this->db->bind(':id', $id);
        $resultado = $this->db->single();
        return $resultado ?: null;
    }

    public function listar(): array {
        $this->db->query("SELECT * FROM {$this->tabla}");
        return $this->db->resultSet() ?: [];
    }
}

// ─── Implementación Mock (para pruebas) ──────────────────────────────────────

class MockInventario implements ImplementacionRepositorio {
    private array $almacen = [];
    private int   $autoIncrement = 1;

    public function guardar(array $entidad): bool {
        $entidad['id_mock'] = $this->autoIncrement++;
        $this->almacen[]    = (object)$entidad;
        return true;
    }

    public function actualizar(array $entidad): bool {
        foreach ($this->almacen as &$item) {
            if (isset($entidad['id_mock']) && $item->id_mock == $entidad['id_mock']) {
                foreach ($entidad as $k => $v) {
                    $item->$k = $v;
                }
                return true;
            }
        }
        return false;
    }

    public function eliminar(int $id): bool {
        $this->almacen = array_values(
            array_filter($this->almacen, fn($i) => $i->id_mock !== $id)
        );
        return true;
    }

    public function buscar(int $id): ?object {
        foreach ($this->almacen as $item) {
            if ($item->id_mock === $id) return $item;
        }
        return null;
    }

    public function listar(): array {
        return $this->almacen;
    }
}

// ─── Abstracción (Bridge) ─────────────────────────────────────────────────────

class Repositorio {
    private ImplementacionRepositorio $implementacion;

    public function __construct(ImplementacionRepositorio $implementacion) {
        $this->implementacion = $implementacion;
    }

    public function guardar(array $entidad): bool {
        return $this->implementacion->guardar($entidad);
    }

    public function actualizar(array $entidad): bool {
        return $this->implementacion->actualizar($entidad);
    }

    public function eliminar(int $id): bool {
        return $this->implementacion->eliminar($id);
    }

    public function buscar(int $id): ?object {
        return $this->implementacion->buscar($id);
    }

    public function listar(): array {
        return $this->implementacion->listar();
    }
}
