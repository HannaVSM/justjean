<?php

class AuthController extends Controller {

    /**
     * Carga la vista de login o redirige al dashboard si ya hay una sesión activa.
     */
    public function index() {
        // Si el usuario ya está logueado, redirigir a su dashboard correspondiente
        if (isset($_SESSION['idUsuario'])) {
            if ($_SESSION['rol'] == 'Administrador') {
                header('Location: ' . URLROOT . 'administrador/dashboard');
            } else {
                header('Location: ' . URLROOT . 'operario/dashboard');
            }
            exit();
        }

        // Si no hay sesión, muestra la vista de login
        $this->view('auth/login');
    }

    /**
     * Procesa la solicitud de inicio de sesión desde el formulario.
     */
    public function login() {
        // Asegurarse de que el método de solicitud sea POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitizar los datos del formulario
            $login = trim($_POST['login']);
            $password = trim($_POST['password']);

            // Cargar el modelo de Usuario
            $userModel = $this->model('Usuario');

            // Intentar loguear al usuario
            $loggedInUser = $userModel->login($login, $password);

            if ($loggedInUser) {
                // Si el login es exitoso, crear la sesión
                $this->createUserSession($loggedInUser);
            } else {
                // Si falla, recargar la vista de login con un error
                $data = [
                    'error' => 'El usuario o la contraseña son incorrectos.'
                ];
                $this->view('auth/login', $data);
            }
        } else {
            // Si no es POST, redirigir a la página de login
            header('Location: ' . URLROOT . 'auth/index');
        }
    }
    

    /**
     * Crea las variables de sesión para el usuario.
     * @param object $user Objeto con los datos del usuario.
     */
    public function createUserSession($user) {
        $_SESSION['idUsuario'] = $user->idUsuario;
        $_SESSION['nombre'] = $user->nombre;
        $_SESSION['rol'] = $user->rol;

        // Redirigir según el rol
        if ($user->rol == 'Administrador') {
            header('Location: ' . URLROOT . 'administrador/dashboard');
        } else {
            header('Location: ' . URLROOT . 'operario/dashboard');
        }
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout() {
        unset($_SESSION['idUsuario']);
        unset($_SESSION['nombre']);
        unset($_SESSION['rol']);
        session_destroy();
        header('Location: ' . URLROOT . 'auth/index');
    }
}