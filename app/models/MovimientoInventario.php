<?php

class MovimientoInventario {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Registra un nuevo movimiento de inventario (entrada o salida).
     * Corresponde al método 'registrar' del Diagrama de Clases.
     * @param array $data Datos del movimiento.
     * @return bool
     */
    public function registrar($data) {
        $this->db->query('INSERT INTO movimientos_inventario (idInsumo, idUsuario, tipoMovimiento, cantidad, idPedido, idOrdenCompra) 
                         VALUES (:idInsumo, :idUsuario, :tipoMovimiento, :cantidad, :idPedido, :idOrdenCompra)');
        
        // Vincular valores
        $this->db->bind(':idInsumo', $data['idInsumo']);
        $this->db->bind(':idUsuario', $data['idUsuario']);
        $this->db->bind(':tipoMovimiento', $data['tipoMovimiento']);
        $this->db->bind(':cantidad', $data['cantidad']);
        $this->db->bind(':idPedido', $data['idPedido']);
        $this->db->bind(':idOrdenCompra', $data['idOrdenCompra']);

        return $this->db->execute();
    }

    /**
     * Obtiene todos los movimientos (para futuros reportes).
     * @return array
     */
    public function obtenerTodos() {
        $this->db->query('SELECT mi.*, i.nombre as nombreInsumo, u.nombre as nombreUsuario 
                         FROM movimientos_inventario mi
                         JOIN insumos i ON mi.idInsumo = i.idInsumo
                         JOIN usuarios u ON mi.idUsuario = u.idUsuario
                         ORDER BY mi.fecha DESC');
        return $this->db->resultSet();
    }

    public function getRecientes($limite = 5) {
    $this->db->query("
        SELECT 
            mi.*, 
            i.nombre AS insumo, 
            u.nombre AS operario
        FROM movimientos_inventario mi
        JOIN insumos i ON mi.idInsumo = i.idInsumo
        JOIN usuarios u ON mi.idUsuario = u.idUsuario
        ORDER BY mi.fecha DESC
        LIMIT :limite
    ");

    $this->db->bind(':limite', $limite, PDO::PARAM_INT);

    return $this->db->resultSet();
}
}