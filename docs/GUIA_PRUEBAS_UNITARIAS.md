# Guía Completa de Pruebas Unitarias

## 📚 Tabla de Contenidos
1. [Introducción](#introducción)
2. [Comandos para Ejecutar Pruebas](#comandos-para-ejecutar-pruebas)
3. [Tipos de Pruebas](#tipos-de-pruebas)
4. [Estructura de Pruebas con Pest](#estructura-de-pruebas-con-pest)
5. [Factories](#factories)
6. [Solución de Problemas](#solución-de-problemas)

---

## 🎯 Introducción

Tu proyecto utiliza **Pest PHP**, un framework de testing moderno para PHP basado en PHPUnit. Las pruebas te permiten verificar que tu código funciona correctamente y prevenir errores futuros.

---

## ⚡ Comandos para Ejecutar Pruebas

### Ejecutar TODAS las pruebas
```bash
php artisan test
```
O también:
```bash
vendor\bin\pest
```

### Ejecutar solo pruebas UNITARIAS
```bash
php artisan test --testsuite=Unit
```
O:
```bash
vendor\bin\pest tests/Unit
```

### Ejecutar solo pruebas de FUNCIONALIDAD
```bash
php artisan test --testsuite=Feature
```
O:
```bash
vendor\bin\pest tests/Feature
```

### Ejecutar un archivo específico de pruebas
```bash
vendor\bin\pest tests/Unit/Models/NotificationHelperTest.php
```

### Ejecutar pruebas con un filtro por nombre
```bash
php artisan test --filter=NotificationHelper
```

### Ejecutar pruebas con más detalles (verboso)
```bash
php artisan test --verbose
```
O:
```bash
vendor\bin\pest -v
```

### Ejecutar pruebas y detener en el primer error
```bash
php artisan test --stop-on-failure
```

### Ver cobertura de código (requiere Xdebug)
```bash
php artisan test --coverage
```

---

## 📂 Tipos de Pruebas

### 1. Pruebas Unitarias (Unit Tests)
Se encuentran en: `tests/Unit/`

Son pruebas que verifican componentes individuales de tu código (funciones, clases, métodos) de forma aislada.

**Ejemplo:**
```php
test('bookingConfirmed crea notificacion correcta', function () {
    $user = User::factory()->create();
    
    NotificationHelper::bookingConfirmed($user, $booking);
    
    expect(Notification::where('user_id', $user->id)->exists())->toBeTrue();
});
```

### 2. Pruebas de Funcionalidad (Feature Tests)
Se encuentran en: `tests/Feature/`

Son pruebas que verifican funcionalidades completas de la aplicación (rutas, formularios, flujos de usuario).

**Ejemplo:**
```php
test('users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard'));
});
```

---

## 🧪 Estructura de Pruebas con Pest

### Anatomía de una prueba con Pest

```php
<?php

use App\Models\User;

// Importante: Indicar que se usa el TestCase de Laravel
uses(Tests\TestCase::class);
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

// Definir un test
test('puede crear un usuario', function () {
    // 1. Preparar (Arrange)
    $userData = [
        'name' => 'Juan Pérez',
        'email' => 'juan@example.com',
    ];
    
    // 2. Actuar (Act)
    $user = User::create($userData);
    
    // 3. Afirmar (Assert)
    expect($user->name)->toBe('Juan Pérez');
    expect($user->email)->toBe('juan@example.com');
});

// También puedes usar it() para tests más descriptivos
it('returns true when user is active', function () {
    $user = User::factory()->create(['is_active' => true]);
    
    expect($user->isActive())->toBeTrue();
});
```

### Expectativas comunes en Pest

```php
// Verificar valores
expect($value)->toBe(10);
expect($value)->toEqual(10);
expect($value)->toBeTrue();
expect($value)->toBeFalse();
expect($value)->toBeNull();

// Verificar tipos
expect($value)->toBeString();
expect($value)->toBeInt();
expect($value)->toBeArray();

// Verificar arrays y colecciones
expect($array)->toHaveCount(5);
expect($array)->toContain('valor');
expect($array)->toHaveKey('clave');

// Verificar strings
expect($string)->toContain('texto');
expect($string)->toStartWith('Hola');
expect($string)->toEndWith('Adiós');

// Verificar base de datos
expect(User::count())->toBe(10);
```

---

## 🏭 Factories

Las **factories** son clases que generan datos de prueba para tus modelos.

### Ubicación
Las factories se encuentran en: `database/factories/`

### Crear una Factory

Ya hemos creado las siguientes factories para ti:

#### UserFactory.php
```php
User::factory()->create();  // Crea un usuario y lo guarda en BD
User::factory()->make();    // Crea un usuario sin guardarlo
```

#### ExperienceFactory.php
```php
Experience::factory()->create();
Experience::factory()->create([
    'title' => 'Tour Personalizado',
    'price' => 150.00,
]);
```

#### BookingFactory.php
```php
Booking::factory()->create();
Booking::factory()->confirmed()->create();  // Reserva confirmada
Booking::factory()->completed()->create();   // Reserva completada
Booking::factory()->cancelled()->create();   // Reserva cancelada
```

### Usar Factories en Pruebas

```php
test('puede crear una reserva', function () {
    // Crear usuarios
    $tourist = User::factory()->create();
    $guide = User::factory()->create();
    
    // Crear experiencia
    $experience = Experience::factory()->create([
        'user_id' => $guide->id,
    ]);
    
    // Crear reserva
    $booking = Booking::factory()->create([
        'user_id' => $tourist->id,
        'experience_id' => $experience->id,
    ]);
    
    expect($booking->user_id)->toBe($tourist->id);
    expect($booking->experience_id)->toBe($experience->id);
});
```

---

## 🔧 Solución de Problemas

### Error: "A facade root has not been set"
**Solución:** Asegúrate de que tu test use el TestCase de Laravel:
```php
uses(Tests\TestCase::class);
```

### Error: "Class 'Database\Factories\XFactory' not found"
**Solución:** Regenera el autoload de Composer:
```bash
composer dump-autoload
```

### Error: "SQLSTATE[HY000]: General error"
**Solución:** Verifica que las columnas en tu factory coincidan con las de tu migración. Usa solo las columnas que existen en la base de datos.

### Las pruebas unitarias no encuentran la base de datos
**Solución:** Las pruebas unitarias puras no deberían acceder a la base de datos. Si necesitas acceder a la BD, usa:
```php
uses(Tests\TestCase::class);
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);
```

### Limpiar la base de datos después de cada test
Ya está configurado con `RefreshDatabase`, que reinicia la BD en cada test.

---

## 📝 Ejemplo Completo de Test

```php
<?php

use App\Models\User;
use App\Models\Experience;
use App\Models\Booking;
use App\Helpers\NotificationHelper;

uses(Tests\TestCase::class);
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('el helper crea notificación de reserva confirmada', function () {
    // Preparar
    $tourist = User::factory()->create();
    $guide = User::factory()->create();
    $experience = Experience::factory()->create(['user_id' => $guide->id]);
    $booking = Booking::factory()->create([
        'user_id' => $tourist->id,
        'experience_id' => $experience->id,
    ]);
    
    // Actuar
    NotificationHelper::bookingConfirmed($tourist, $booking);
    
    // Afirmar
    $notification = $tourist->notifications()->first();
    expect($notification)->not->toBeNull();
    expect($notification->type)->toBe('booking_confirmed');
    expect($notification->title)->toBe('¡Reserva Confirmada!');
});
```

---

## 🎓 Recursos Adicionales

- **Documentación de Pest:** https://pestphp.com/
- **Documentación de Laravel Testing:** https://laravel.com/docs/testing
- **Factories de Laravel:** https://laravel.com/docs/eloquent-factories

---

## ✅ Resumen de Comandos Más Usados

```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar solo Unit tests
php artisan test --testsuite=Unit

# Ejecutar un archivo específico
vendor\bin\pest tests/Unit/Models/NotificationHelperTest.php

# Detener en el primer error
php artisan test --stop-on-failure

# Ver más detalles
php artisan test --verbose

# Regenerar autoload si hay errores
composer dump-autoload
```

---

**Nota:** Las pruebas son fundamentales para mantener la calidad del código. Ejecuta `php artisan test` regularmente para asegurarte de que todo funciona correctamente.

