<?php

class Usuario {
    private $db;

    public function __construct() {
        // Crea una nueva instancia de la clase Database para la conexión
        $this->db = new Database;
    }

    /**
     * Busca un usuario por su 'login' y verifica su contraseña.
     * Basado en el requerimiento de seguridad RFN4 y el caso de uso de login.
     * @param string $login El nombre de usuario.
     * @param string $password La contraseña en texto plano.
     * @return mixed Retorna el objeto del usuario si las credenciales son correctas, de lo contrario retorna false.
     */
    public function login($login, $password) {
        // Busca al usuario por su login en la base de datos
        $row = $this->findByLogin($login);

        // Si se encontró un usuario con ese login
        if ($row) {
            $hashed_password = $row->password;
            // Verifica que la contraseña ingresada coincida con la contraseña hasheada en la BD
            if (password_verify($password, $hashed_password)) {
                return $row; // Las credenciales son correctas
            } else {
                return false; // La contraseña es incorrecta
            }
        } else {
            return false; // El usuario no fue encontrado
        }
    }

    /**
     * Encuentra un usuario en la base de datos por su campo 'login'.
     * @param string $login El login del usuario a buscar.
     * @return mixed Retorna el objeto del usuario si se encuentra, de lo contrario false.
     */
    public function findByLogin($login) {
        $this->db->query('SELECT * FROM usuarios WHERE login = :login');
        // Vincula el valor de login
        $this->db->bind(':login', $login);

        // Ejecuta y obtiene un único registro
        $row = $this->db->single();

        return $row;
    }

    /**
     * Encuentra un usuario por su ID.
     * @param int $id El ID del usuario.
     * @return mixed
     */
    public function findById($id) {
        $this->db->query('SELECT * FROM usuarios WHERE idUsuario = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row;
    }

    /**
     * Verifica si el rol del usuario en sesión cumple con el permiso requerido.
     * Este método corresponde al método 'verificarPermiso' del diagrama de clases.
     * @param string $rolRequerido El rol que se requiere para acceder (ej: 'Administrador').
     * @return bool True si el usuario tiene el permiso, false en caso contrario.
     */
    public function verificarPermiso($rolRequerido) {
        if (isset($_SESSION['rol'])) {
            // Compara el rol de la sesión con el rol requerido
            return $_SESSION['rol'] == $rolRequerido;
        }
        return false;
    }
}