# Usar una imagen base de PHP
FROM php:8.1-fpm

# Instalar las dependencias necesarias
RUN apt-get update && apt-get install -y libpq-dev git unzip && rm -rf /var/lib/apt/lists/*

# Instalar Composer (el gestor de dependencias de PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar el código al contenedor
COPY . /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias con Composer
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto 80
EXPOSE 80

# Configurar el comando para ejecutar el servidor web
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]
