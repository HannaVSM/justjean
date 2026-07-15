<?php

class Insumo {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Obtiene todos los insumos de la base de datos.
     * @return array
     */
    public function obtenerTodos() {
        $this->db->query('SELECT * FROM insumos ORDER BY nombre ASC');
        return $this->db->resultSet();
    }

    /**
     * Obtiene un insumo por su ID.
     * @param int $id
     * @return object
     */
    public function obtenerPorId($id) {
        $this->db->query('SELECT * FROM insumos WHERE idInsumo = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Crea un nuevo insumo en la base de datos.
     * Corresponde al Caso de Uso 1: Registrar nuevo insumo.
     * @param array $data
     * @return bool
     */
    public function crear($data) {
        $this->db->query('INSERT INTO insumos (nombre, descripcion, tipo, unidadMedida, stockActual, stockMinimo, costo) 
                         VALUES (:nombre, :descripcion, :tipo, :unidadMedida, :stockActual, :stockMinimo, :costo)');
        
        $this->db->bind(':nombre', $data['nombre']);
        $this->db->bind(':descripcion', $data['descripcion']);
        $this->db->bind(':tipo', $data['tipo']);
        $this->db->bind(':unidadMedida', $data['unidadMedida']);
        $this->db->bind(':stockActual', $data['stockActual']);
        $this->db->bind(':stockMinimo', $data['stockMinimo']);
        $this->db->bind(':costo', $data['costo']);

        return $this->db->execute();
    }

    /**
     * Actualiza la información de un insumo.
     * @param array $data
     * @return bool
     */
    public function actualizar($data) {
        $this->db->query('UPDATE insumos SET nombre = :nombre, descripcion = :descripcion, tipo = :tipo, 
                         unidadMedida = :unidadMedida, stockMinimo = :stockMinimo, costo = :costo 
                         WHERE idInsumo = :id');

        $this->db->bind(':id', $data['idInsumo']);
        $this->db->bind(':nombre', $data['nombre']);
        $this->db->bind(':descripcion', $data['descripcion']);
        $this->db->bind(':tipo', $data['tipo']);
        $this->db->bind(':unidadMedida', $data['unidadMedida']);
        $this->db->bind(':stockMinimo', $data['stockMinimo']);
        $this->db->bind(':costo', $data['costo']);

        return $this->db->execute();
    }

    /**
     * Elimina un insumo.
     * @param int $id
     * @return bool
     */
    public function eliminar($id) {
        $this->db->query('DELETE FROM insumos WHERE idInsumo = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // --- Métodos de Lógica de Negocio (del Diagrama de Clases) ---

    /**
     * Actualiza el stock de un insumo específico.
     * @param int $id El ID del insumo.
     * @param int $cantidad La cantidad a sumar (positivo) o restar (negativo).
     * @return bool
     */
    public function actualizarStock($id, $cantidad) {
        $this->db->query('UPDATE insumos SET stockActual = stockActual + :cantidad WHERE idInsumo = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':cantidad', $cantidad);
        return $this->db->execute();
    }

    /**
     * Verifica si hay suficiente stock de un insumo.
     * @param int $id El ID del insumo.
     * @param int $cantidadNecesaria La cantidad requerida.
     * @return bool
     */
    public function verificarDisponibilidad($id, $cantidadNecesaria) {
        $insumo = $this->obtenerPorId($id);
        if ($insumo) {
            return $insumo->stockActual >= $cantidadNecesaria;
        }
        return false;
    }

    /**
     * Verifica si el stock de un insumo está por debajo del mínimo definido.
     * @param int $id El ID del insumo.
     * @return bool
     */
    public function estaBajoStock($id) {
        $insumo = $this->obtenerPorId($id);
        if ($insumo) {
            return $insumo->stockActual < $insumo->stockMinimo;
        }
        return false;
    }
     /**
     * Actualiza únicamente el campo stockMinimo de un insumo.
     * @param int $id El ID del insumo.
     * @param int $minimo El nuevo valor para el stock mínimo.
     * @return bool
     */
    public function actualizarStockMinimo($id, $minimo) {
        $this->db->query('UPDATE insumos SET stockMinimo = :minimo WHERE idInsumo = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':minimo', $minimo);
        return $this->db->execute();
    }
    public function contarTodos() {
        $this->db->query('SELECT COUNT(*) as total FROM insumos');
        return $this->db->single()->total;
    }

    public function contarBajoStock() {
        $this->db->query('SELECT COUNT(*) as total FROM insumos WHERE stockActual < stockMinimo AND stockMinimo > 0');
        return $this->db->single()->total;
    }
}