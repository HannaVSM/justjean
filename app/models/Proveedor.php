<?php

/**
 * Modelo Proveedor
 * Integra el patrón Bridge: usa la clase Repositorio como capa de abstracción
 * sobre la implementación concreta (MySQLInventario).
 * Esto permite cambiar la implementación (ej: MockInventario para pruebas)
 * sin modificar nada de este modelo ni del controller.
 */
class Proveedor {
    private $db;
    /** @var Repositorio — patrón Bridge */
    private Repositorio $repositorio;

    public function __construct() {
        $this->db = new Database;

        // Patrón Bridge: se inyecta la implementación MySQL concreta.
        // Para pruebas sin BD: new Repositorio(new MockInventario())
        $this->repositorio = new Repositorio(
            new MySQLInventario($this->db, 'proveedores', 'idProveedor')
        );
    }

    /**
     * Obtiene todos los proveedores — usa el Bridge (listar).
     */
    public function obtenerTodos() {
        return $this->repositorio->listar();
    }

    /**
     * Obtiene un proveedor por su ID — usa el Bridge (buscar).
     */
    public function obtenerPorId($id) {
        return $this->repositorio->buscar((int)$id);
    }

    /**
     * Crea un nuevo proveedor — usa el Bridge (guardar).
     */
    public function crear($data) {
        return $this->repositorio->guardar([
            'nombre'   => $data['nombre'],
            'contacto' => $data['contacto'],
            'email'    => $data['email'],
        ]);
    }

    /**
     * Actualiza un proveedor existente — usa el Bridge (actualizar).
     */
    public function actualizar($data) {
        return $this->repositorio->actualizar([
            'idProveedor' => (int)$data['idProveedor'],
            'nombre'      => $data['nombre'],
            'contacto'    => $data['contacto'],
            'email'       => $data['email'],
        ]);
    }

    /**
     * Elimina un proveedor — usa el Bridge (eliminar).
     */
    public function eliminar($id) {
        return $this->repositorio->eliminar((int)$id);
    }

    public function contarTodos() {
        return count($this->repositorio->listar());
    }
}
