<?php

/**
 * Router para PHP built-in server.
 * Reemplaza la funcionalidad del .htaccess en entornos sin Apache (Railway, etc.)
 */

$requestUri = $_SERVER['REQUEST_URI'];

// Separar path de query string
$path = parse_url($requestUri, PHP_URL_PATH);

// Si es un archivo estático que existe (css, js, imágenes), servirlo directamente
if ($path !== '/' && file_exists(__DIR__ . $path)) {
    return false;
}

// Todo lo demás va al index.php
// Extraer la parte de la URL después del '/' inicial y ponerla en $_GET['url']
$path = ltrim($path, '/');

if (!empty($path)) {
    $_GET['url'] = $path;
}

require_once __DIR__ . '/index.php';
