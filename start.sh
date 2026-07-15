#!/bin/bash

# Busca el directorio de extensiones de PHP
EXT_DIR=$(php -r "echo ini_get('extension_dir');")
echo "Extension dir: $EXT_DIR"

# Muestra las extensiones disponibles para diagnóstico
echo "PDO extensions encontradas:"
find "$EXT_DIR" -name "*pdo*" 2>/dev/null || echo "Ninguna encontrada en $EXT_DIR"

# Inicia el servidor cargando pdo_mysql explícitamente
exec php \
  -d extension=pdo \
  -d extension=pdo_mysql \
  -S 0.0.0.0:$PORT \
  -t public \
  public/router.php
