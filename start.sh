#!/bin/bash

echo "PHP version: $(php --version | head -1)"
echo "Extensiones PDO: $(php -m | grep -i pdo)"

exec php -S 0.0.0.0:$PORT -t public public/router.php
