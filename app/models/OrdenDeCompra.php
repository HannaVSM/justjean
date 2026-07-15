<?php

class OrdenDeCompra {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Crea una nueva orden de compra y devuelve su ID.
     * @param array $data Datos de la orden (idProveedor, fecha, estado).
     * @return int|false El ID de la nueva orden o false si falla.
     */
    public function crear($data) {
        $this->db->query('INSERT INTO ordenes_compra (idProveedor, fecha, estado) VALUES (:idProveedor, :fecha, :estado)');
        
        $this->db->bind(':idProveedor', $data['idProveedor']);
        $this->db->bind(':fecha', $data['fecha']);
        $this->db->bind(':estado', $data['estado']);

        // Ejecutar y si tiene éxito, devolver el último ID insertado
        if ($this->db->execute()) {
            return $this->db->dbh->lastInsertId();
        } else {
            return false;
        }
    }

    /**
     * Obtiene todas las órdenes de compra con el nombre del proveedor.
     * @return array
     */
    public function obtenerTodas() {
        $this->db->query('SELECT oc.*, p.nombre as nombreProveedor 
                         FROM ordenes_compra oc
                         JOIN proveedores p ON oc.idProveedor = p.idProveedor
                         ORDER BY oc.fecha DESC');
        return $this->db->resultSet();
    }
    
    /**
     * Obtiene una orden de compra específica por su ID.
     * @param int $id
     * @return object
     */
    public function obtenerPorId($id) {
        $this->db->query('SELECT oc.*, p.nombre as nombreProveedor 
                         FROM ordenes_compra oc
                         JOIN proveedores p ON oc.idProveedor = p.idProveedor
                         WHERE oc.idOrdenCompra = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Actualiza el estado de una orden de compra.
     * @param int $id
     * @param string $nuevoEstado
     * @return bool
     */
    public function actualizarEstado($id, $nuevoEstado) {
        $this->db->query('UPDATE ordenes_compra SET estado = :estado WHERE idOrdenCompra = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':estado', $nuevoEstado);
        return $this->db->execute();
    }
}