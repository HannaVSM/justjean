FROM php:8.3-cli

# Instala extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Copia el proyecto
WORKDIR /app
COPY . .

# Expone el puerto que Railway asigna
EXPOSE 8080

# Arranca el servidor
CMD php -S 0.0.0.0:${PORT:-8080} -t public public/router.php
