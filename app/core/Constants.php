<?php

// --- Configuración de la Base de Datos ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'creaciones_justean_db');

// --- Configuración de Rutas ---

// APPROOT: ruta física (ESTA ESTÁ BIEN)
define('APPROOT', dirname(dirname(__DIR__)));

// 🔥 URLROOT DINÁMICO
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];

define('URLROOT', getenv('APP_URL') ?: 'http://localhost/Sistema-inventario-justjean-main/');