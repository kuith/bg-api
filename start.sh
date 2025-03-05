#!/bin/bash
# Instala PHP (si no está instalado)
apt-get update
apt-get install -y php-cli php-zip

# Ejecuta el servidor PHP
php -S 0.0.0.0:8080 -t public
