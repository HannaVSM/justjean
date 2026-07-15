<?php

// Inicia una sesión de PHP para manejar los datos del usuario logueado.
session_start();

// Carga el archivo principal de la aplicación (router)
require_once(__DIR__ . '/../app/core/App.php');

// Carga constantes (DB, URLROOT, etc.)
require_once(__DIR__ . '/../app/core/Constants.php');

// Controlador base
require_once(__DIR__ . '/../app/core/Controller.php');

// Patrones de diseño
require_once(__DIR__ . '/../app/patterns/Patterns.php');

// Conexión a base de datos
require_once(__DIR__ . '/../app/core/Database.php');

// Inicializa la aplicación
$app = new App();

?>