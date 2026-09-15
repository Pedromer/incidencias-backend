# Backend - Sistema de Gestion de Incidencias Tecnicas

API REST del sistema de mesa tecnica para la ESET-UNQ. El backend esta desarrollado con Laravel, Sanctum y MariaDB. El frontend se ejecuta en un repositorio independiente y se comunica exclusivamente mediante HTTP y JSON.

## Tecnologias

- PHP 8.2 o superior
- Laravel 12
- Laravel Sanctum
- MariaDB/MySQL
- Composer

## Instalacion local

Clonar la rama de trabajo:

```bash
git clone -b main-2 https://github.com/Pedromer/incidencias-backend.git
cd incidencias-backend
composer install
cp .env.example .env
php artisan key:generate
```

Crear la base de datos en MariaDB:

```sql
CREATE DATABASE incidencias CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Configurar `.env` con los datos locales:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=incidencias
DB_USERNAME=root
DB_PASSWORD=la_contrasena_local
CORS_ALLOWED_ORIGINS=http://localhost:5500,http://127.0.0.1:5500
```

El usuario de MariaDB debe tener permisos sobre la base `incidencias`. Luego ejecutar:

```bash
php artisan config:clear
php artisan migrate:fresh --seed
```

Los seeders crean las categorias obligatorias y los usuarios de prueba. No es necesario crear seeders manualmente.

## Ejecucion

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

La API queda disponible en `http://127.0.0.1:8000/api`.

## Usuarios de prueba

```text
Cliente: cliente@test.com / 123
Tecnico: tecnico@test.com / 123
```

## Endpoints principales

```text
POST /api/login
POST /api/logout
GET  /api/user
GET  /api/categorias
GET  /api/incidencias
POST /api/incidencias
GET  /api/incidencias/{id}
PUT  /api/incidencias/{id}/tomar
PUT  /api/incidencias/{id}/resolver
GET  /api/incidencias/{id}/comentarios
POST /api/incidencias/{id}/comentarios
```

Las rutas protegidas requieren el encabezado `Authorization: Bearer TOKEN`. Los roles se identifican con el campo `tipo`, cuyos valores son `cliente` y `tecnico`.

## Reglas implementadas

- El cliente solo puede ver sus propias incidencias.
- El tecnico puede ver todas las incidencias.
- Solo un tecnico puede tomar una incidencia `abierto`.
- Al tomarla pasa a `en_curso`.
- Solo el tecnico asignado puede resolverla.
- Para resolverla se requiere una descripcion en `resolucion`.
- Al resolverla pasa a `finalizado`.
- Los errores y validaciones se devuelven en JSON.

## Despliegue

En el servidor de despliegue se necesitan PHP, Composer, MariaDB y un servidor web. Configurar las variables de `.env`, ejecutar `composer install --no-dev`, generar `APP_KEY`, correr `php artisan migrate --seed` y configurar el servidor web para apuntar al directorio `public/`.

El frontend debe publicarse por separado. Agregar su dominio a `CORS_ALLOWED_ORIGINS` y limpiar la configuracion con `php artisan config:clear` después de modificar `.env`.
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
