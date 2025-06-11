# Dockerfile
FROM php:8.1-apache

# Instala extensiones de PHP necesarias (mysqli, pdo_mysql). Ajusta si requieres más extensiones.
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilita mod_rewrite si tu app lo requiere
RUN a2enmod rewrite

# Copia el código PHP al directorio raíz de Apache
COPY src/ /var/www/html/

# Opcional: ajustar permisos (si tu app escribe en carpetas dentro de src/)
# RUN chown -R www-data:www-data /var/www/html

# Expone el puerto 80 (la imagen ya lo hace, pero por claridad):
EXPOSE 80

# El CMD/ENTRYPOINT de la imagen base arranca Apache en foreground automáticamente
