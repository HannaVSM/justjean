<?php

class Pedido {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Obtiene todos los pedidos de la base de datos, mostrando los más recientes primero.
     * @return array
     */
    public function obtenerTodos() {
        $this->db->query('SELECT * FROM pedidos ORDER BY fecha DESC');
        return $this->db->resultSet();
    }
    
    /**
     * Obtiene un pedido por su ID.
     * @param int $id
     * @return object
     */
    public function obtenerPorId($id) {
        $this->db->query('SELECT * FROM pedidos WHERE idPedido = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Crea un nuevo pedido.
     * @param array $data Datos del pedido (cliente, fecha, estado).
     * @return bool
     */
    public function crear($data) {
        $this->db->query('INSERT INTO pedidos (cliente, fecha, estado) VALUES (:cliente, :fecha, :estado)');
        $this->db->bind(':cliente', $data['cliente']);
        $this->db->bind(':fecha', $data['fecha']);
        $this->db->bind(':estado', $data['estado']);
        return $this->db->execute();
    }

    /**
     * Actualiza el estado de un pedido.
     * Corresponde al método 'actualizarEstado' del Diagrama de Clases.
     * @param int $id El ID del pedido.
     * @param string $nuevoEstado El nuevo estado.
     * @return bool
     */
    public function actualizarEstado($id, $nuevoEstado) {
        $this->db->query('UPDATE pedidos SET estado = :estado WHERE idPedido = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':estado', $nuevoEstado);
        return $this->db->execute();
    }

    /**
     * Asigna un insumo con una cantidad específica a un pedido en la tabla detalle_pedido.
     * Corresponde al método 'asignarInsumo' del Diagrama de Clases.
     * @param int $idPedido
     * @param int $idInsumo
     * @param int $cantidad
     * @return bool
     */
    public function asignarInsumo($idPedido, $idInsumo, $cantidad) {
        $this->db->query('INSERT INTO detalle_pedido (idPedido, idInsumo, cantidadAsignada) VALUES (:idPedido, :idInsumo, :cantidad)');
        $this->db->bind(':idPedido', $idPedido);
        $this->db->bind(':idInsumo', $idInsumo);
        $this->db->bind(':cantidad', $cantidad);
        return $this->db->execute();
    }
    public function contarActivos() {
        $this->db->query("SELECT COUNT(*) as total FROM pedidos WHERE estado IN ('Registrado', 'En Produccion')");
        return $this->db->single()->total;
    }
}