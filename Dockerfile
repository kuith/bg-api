# Usa una imagen base de PHP 8.2
FROM php:8.2-cli

# Instala las dependencias necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Crea un nuevo usuario (sin privilegios de root) y configura los permisos
RUN useradd -ms /bin/bash symfonyuser
USER symfonyuser

# Copia el código de tu proyecto al contenedor
WORKDIR /home/symfonyuser
COPY . .

# Instala las dependencias de Composer
RUN composer install --optimize-autoloader


# Expone el puerto para servir la aplicación
EXPOSE 8080

# Comando para iniciar la aplicación
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
