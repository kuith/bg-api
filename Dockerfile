# Usa una imagen base de PHP 8.2
FROM php:8.2-cli

# Instala las dependencias necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia el código de tu proyecto al contenedor
COPY . /var/www/html

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Instala las dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Expone el puerto para servir la aplicación
EXPOSE 8080

# Comando para iniciar la aplicación
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
