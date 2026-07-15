<?php

// --- Configuración de la Base de Datos ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'creaciones_justean_db');

// --- Configuración de Rutas ---

// APPROOT: ruta física (ESTA ESTÁ BIEN)
define('APPROOT', dirname(dirname(__DIR__)));

// URLROOT DINÁMICO — usa APP_URL si está seteada (Railway), si no construye desde el request
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';

define('URLROOT', getenv('APP_URL') ?: $protocol . '://' . $host . '/');