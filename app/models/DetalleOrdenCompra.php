<?php

class DetalleOrdenCompra {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Agrega un insumo (una línea de detalle) a una orden de compra existente.
     * @param array $data Contiene idOrdenCompra, idInsumo, cantidadSolicitada y costoUnitario.
     * @return bool
     */
    public function agregarInsumo($data) {
        $this->db->query('INSERT INTO detalle_orden_compra (idOrdenCompra, idInsumo, cantidadSolicitada, costoUnitario) 
                         VALUES (:idOrdenCompra, :idInsumo, :cantidadSolicitada, :costoUnitario)');
        
        $this->db->bind(':idOrdenCompra', $data['idOrdenCompra']);
        $this->db->bind(':idInsumo', $data['idInsumo']);
        $this->db->bind(':cantidadSolicitada', $data['cantidadSolicitada']);
        $this->db->bind(':costoUnitario', $data['costoUnitario']);

        return $this->db->execute();
    }

    /**
     * Obtiene todos los insumos (líneas de detalle) de una orden de compra específica.
     * @param int $idOrdenCompra
     * @return array
     */
    public function obtenerPorOrdenId($idOrdenCompra) {
        $this->db->query('SELECT doc.*, i.nombre as nombreInsumo, i.unidadMedida
                         FROM detalle_orden_compra doc
                         JOIN insumos i ON doc.idInsumo = i.idInsumo
                         WHERE doc.idOrdenCompra = :idOrdenCompra');
        $this->db->bind(':idOrdenCompra', $idOrdenCompra);
        return $this->db->resultSet();
    }
}