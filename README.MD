# 📚 Proyecto API de Juegos de Mesa

Este proyecto es una API RESTful para gestionar jugadores, juegos y partidas de mesa.

## 🔧 **Requisitos**

- PHP 8.1 o superior
- Symfony 6.x
- SQLite (para el entorno de desarrollo)
- PHPUnit (para pruebas)

## 🚀 **Instalación**

1. Clona el repositorio:
    ```bash
    git clone https://github.com/kuith/bg-api.git
    cd bg-api
    ```

2. Instala las dependencias con Composer:
    ```bash
    composer install
    ```

3. Crea la base de datos:
    ```bash
    php bin/console doctrine:schema:create
    ```

4. Inicia el servidor local:
    ```bash
    symfony server:start
    ```

5. Accede a la API en `http://localhost:8000`.

---

## 📜 **Documentación de la API**

La documentación completa de los endpoints de la API se encuentra en un archivo separado. Para acceder a ella, haz clic en el siguiente enlace:

- [Ver Endpoints de la API](docs/endpoints.md)

---

## 🧪 **Pruebas**

Para ejecutar las pruebas, utiliza PHPUnit:

```bash
php bin/phpunit
