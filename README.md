# NexLocal

**Plataforma de Experiencias Turísticas Locales**

Sistema de reservas y gestión de experiencias turísticas que conecta viajeros con guías locales en Córdoba, Colombia.

---

## 🚀 Características Principales

- **Sistema de Reservas Completo**: Estados (`pending`, `confirmed`, `in_progress`, `completed`, `cancelled`)
- **Roles de Usuario**: Turistas y Guías con permisos específicos
- **Gestión de Cupos**: Control automático de disponibilidad
- **Sistema de Reseñas**: Los turistas pueden valorar experiencias
- **Búsqueda y Filtros**: Encuentra experiencias por título, ubicación o categoría
- **Panel de Guía**: Dashboard completo para gestionar reservas
- **Notificaciones**: Sistema de alertas para turistas y guías

---

## 📚 Documentación

### Sistema de Reservas
La documentación completa del sistema de estados de reservas está disponible en la carpeta [`docs/`](./docs/):

- **[Manual Completo](./docs/MANUAL_ESTADOS_RESERVAS.md)** - Guía detallada para turistas y guías
- **[Guía Rápida](./docs/GUIA_RAPIDA_ESTADOS.md)** - Referencia rápida con diagramas visuales
- **[Ejemplos de Código](./docs/EJEMPLOS_CODIGO_RESERVAS.md)** - Para desarrolladores
- **[Diagramas de Flujo](./docs/DIAGRAMAS_FLUJO.md)** - Visualizaciones con Mermaid

**Inicio rápido:** Lee primero el [README de docs](./docs/README.md)

---

## 🛠️ Tecnologías

- **Backend**: Laravel 12.x
- **Frontend**: Blade Templates + Tailwind CSS
- **Base de datos**: SQLite (desarrollo) / MySQL (producción)
- **Testing**: Pest PHP
- **Autenticación**: Laravel Breeze

---

## 📋 Requisitos

- PHP >= 8.2
- Composer
- Node.js & npm
- SQLite/MySQL

---

## 🚦 Instalación

```bash
# Clonar repositorio
git clone https://github.com/tu-usuario/nexlocal.git
cd nexlocal

# Instalar dependencias PHP
composer install

# Instalar dependencias JavaScript
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Ejecutar migraciones
php artisan migrate --seed

# Compilar assets
npm run build

# Iniciar servidor
php artisan serve
```

Accede a la aplicación en `http://localhost:8000`

---

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Con coverage
php artisan test --coverage

# Tests específicos
php artisan test --filter=BookingTest
```

---

## 📖 About Laravel

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

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

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
