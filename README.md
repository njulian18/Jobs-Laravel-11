# Custom Background Job Runner for Laravel

Este proyecto proporciona una solución para ejecutar trabajos en segundo plano en Laravel, fuera del sistema de colas predeterminado de Laravel. El sistema permite ejecutar clases y métodos como trabajos en segundo plano, registrando su estado (éxito o error) y ofreciendo un mecanismo de manejo de errores, reintentos y retrasos.

## Características principales:
- Ejecución de trabajos en segundo plano.
- Registro detallado de la ejecución de trabajos (éxito y error).
- Manejo de errores con registro de fallos.
- Reintentos configurables para trabajos fallidos.
- Soporte para retrasos en la ejecución de trabajos.
- Validación de clases y métodos permitidos para evitar ejecuciones no autorizadas.


DB (project)


1 - Clonar el repositorio:

    git clone https://github.com/njulian18/Jobs-Laravel-11.git
    cd Jobs-Laravel-11

2 - Instalar dependencias:

    composer install

3 - Crear el archivo .env:

    cp .env.example .env

4 - Generar la clave de la aplicación:

    php artisan key:generate

5 - Migrar la base de datos:

    php artisan migrate

6 - Copilar:

    npm run dev

7 - Servir el proyecto:

    php artisan serve 



![imagen](https://github.com/user-attachments/assets/83fc5e85-9aaf-40c0-8711-5dc8050166ba)
