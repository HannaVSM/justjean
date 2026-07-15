<?php

// Inicia una sesión de PHP para manejar los datos del usuario logueado.
session_start();

// Carga el archivo principal de la aplicación que actuará como enrutador.
require_once 'app/core/App.php';

// Carga el archivo de configuración de la base de datos y otras constantes.
require_once 'app/core/Constants.php';

// Carga el controlador base para que todos los controladores puedan extender de él.
require_once 'app/core/Controller.php';

// Carga todos los patrones de diseño implementados.
require_once 'app/patterns/Patterns.php';

// Carga la clase para la conexión con la base de datos.
require_once 'app/core/Database.php';

// Crea una nueva instancia de la aplicación para manejar la solicitud.
$app = new App();

?>