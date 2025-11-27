# 🔍 Explicación Detallada de las 5 Fallas en las Pruebas

## 📊 Resumen de Fallas

| # | Test | Problema | Causa Raíz |
|---|------|----------|------------|
| 1 | BookingTest | Booleanos retornan `null` en vez de `true`/`false` | Modelo nuevo sin guardar en BD |
| 2 | ExperienceTest (scopeFeatured) | Campo `is_featured` no existe | La columna no está en las migraciones |
| 3 | ExperienceTest (scopePublished) | Campo `status` no existe | La columna no está en las migraciones |
| 4 | UserTest (suspend/restore) | Campos `is_suspended`, etc. no existen | Las columnas no están en las migraciones |
| 5 | RegistrationTest | Usuario no se autentica | Faltan campos requeridos (role, profile_photo) |

---

## 1️⃣ FALLA 1: BookingTest - Booleanos retornan `null`

### 📍 Código del Test (Línea 12-30)
```php
test('booking casts attributes correctly', function () {
    $booking = new Booking([
        'booking_date' => '2025-11-27 10:00:00',
        'paid_at' => '2025-11-27 09:00:00',
        'total_amount' => '150000.50',
        'tourist_confirmed_completed' => 1,
        'guide_confirmed_completed' => 0,
    ]);
    
    // ❌ FALLA AQUÍ
    expect($booking->tourist_confirmed_completed)->toBeTrue()
        ->and($booking->guide_confirmed_completed)->toBeFalse();
});
```

### 🐛 Problema
El test crea un modelo `new Booking([...])` pero **NO lo guarda en la base de datos**. Los atributos booleanos definidos con `cast` en Laravel necesitan pasar por el proceso de hidratación de Eloquent para convertirse correctamente.

### 💡 Causa Raíz
- `new Booking([...])` solo crea una instancia en memoria
- Los `casts` de Eloquent solo se aplican cuando se recupera de la BD o se guarda
- Los valores crudos (1, 0) no se convierten a booleanos automáticamente

### ✅ Solución
**Opción A:** Guardar el modelo en la BD primero
```php
$booking = Booking::create([
    'user_id' => User::factory()->create()->id,
    'experience_id' => Experience::factory()->create()->id,
    'booking_date' => '2025-11-27 10:00:00',
    'paid_at' => '2025-11-27 09:00:00',
    'total_amount' => '150000.50',
    'tourist_confirmed_completed' => 1,
    'guide_confirmed_completed' => 0,
]);

expect($booking->tourist_confirmed_completed)->toBeTrue()
    ->and($booking->guide_confirmed_completed)->toBeFalse();
```

**Opción B:** Verificar los valores numéricos directamente
```php
expect($booking->tourist_confirmed_completed)->toBe(1)
    ->and($booking->guide_confirmed_completed)->toBe(0);
```

---

## 2️⃣ FALLA 2: ExperienceTest - Campo `is_featured` no existe

### 📍 Código del Test (Línea 26-32)
```php
test('scopeFeatured solo trae experiencias destacadas', function () {
    Experience::factory()->create(['user_id' => $this->user->id, 'is_featured' => true]);
    Experience::factory()->create(['user_id' => $this->user->id, 'is_featured' => false]);

    $featured = Experience::featured()->get();

    expect($featured)->toHaveCount(1)
        ->and($featured->first()->is_featured)->toBeTrue();  // ❌ FALLA
});
```

### 🐛 Problema
La columna `is_featured` **NO EXISTE** en la tabla `experiences`. La migración actual solo tiene estas columnas:
- id, user_id, title, description, location, duration, price
- includes, not_includes, timestamps

### 💡 Causa Raíz
- El test asume que existe una columna `is_featured`
- El test asume que existe un scope `Experience::featured()`
- Ninguno de los dos existe en el código actual

### ✅ Solución

**Opción A:** Eliminar el test (si la funcionalidad no es necesaria)

**Opción B:** Crear la migración y funcionalidad (RECOMENDADO)

**Paso 1:** Crear migración para agregar columna
```bash
php artisan make:migration add_status_and_featured_to_experiences_table
```

**Paso 2:** Contenido de la migración
```php
public function up(): void
{
    Schema::table('experiences', function (Blueprint $table) {
        $table->string('status')->default('draft')->after('not_includes');
        $table->boolean('is_featured')->default(false)->after('status');
    });
}

public function down(): void
{
    Schema::table('experiences', function (Blueprint $table) {
        $table->dropColumn(['status', 'is_featured']);
    });
}
```

**Paso 3:** Agregar scope al modelo `Experience`
```php
// En app/Models/Experience.php

public function scopePublished($query)
{
    return $query->where('status', 'published');
}

public function scopeFeatured($query)
{
    return $query->where('is_featured', true);
}

public function scopePubliclyVisible($query)
{
    return $query->where('status', 'published')
                 ->orderByDesc('is_featured')
                 ->latest();
}
```

---

## 3️⃣ FALLA 3: ExperienceTest - Campo `status` no existe

### 📍 Código del Test (Línea 14-23)
```php
test('scopePublished solo trae experiencias publicadas', function () {
    Experience::factory()->create(['user_id' => $this->user->id, 'status' => 'published']);
    Experience::factory()->create(['user_id' => $this->user->id, 'status' => 'draft']);
    Experience::factory()->create(['user_id' => $this->user->id, 'status' => 'hidden']);

    $published = Experience::published()->get();  // ❌ FALLA

    expect($published)->toHaveCount(1)
        ->and($published->first()->status)->toBe('published');
});
```

### 🐛 Problema
Mismo que el anterior: la columna `status` no existe en la tabla `experiences`.

### ✅ Solución
Misma que la Falla #2 - necesitas crear la migración para agregar las columnas.

---

## 4️⃣ FALLA 4: UserTest - Campos de suspensión no existen

### 📍 Código del Test (Línea 57, 72)
```php
test('suspend method actualiza el estado, razón y fecha', function () {
    $user = User::factory()->create();
    $reason = "Comportamiento inapropiado";

    $user->suspend($reason);
    $user->refresh();

    expect($user->is_suspended)->toBeTrue()  // ❌ FALLA - columna no existe
        ->and($user->suspension_reason)->toBe($reason)
        ->and($user->suspended_at)->not->toBeNull();
});

test('restore method limpia el estado de suspensión', function () {
    // ...
    expect($user->is_suspended)->toBeFalse()  // ❌ FALLA
        ->and($user->suspension_reason)->toBeNull()
        ->and($user->suspended_at)->toBeNull();
});
```

### 🐛 Problema
Las columnas de suspensión **NO EXISTEN** en la tabla `users`:
- `is_suspended`
- `suspension_reason`
- `suspended_at`

Tampoco existen los métodos:
- `$user->suspend($reason)`
- `$user->restore()`
- `$user->isSuspended()`

### ✅ Solución

**Paso 1:** Crear migración
```bash
php artisan make:migration add_suspension_fields_to_users_table
```

**Paso 2:** Contenido de la migración
```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_suspended')->default(false)->after('email_verified_at');
        $table->string('suspension_reason')->nullable()->after('is_suspended');
        $table->timestamp('suspended_at')->nullable()->after('suspension_reason');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['is_suspended', 'suspension_reason', 'suspended_at']);
    });
}
```

**Paso 3:** Agregar métodos al modelo `User`
```php
// En app/Models/User.php

protected $fillable = [
    // ...existentes...
    'is_suspended',
    'suspension_reason',
    'suspended_at',
];

protected function casts(): array
{
    return [
        // ...existentes...
        'is_suspended' => 'boolean',
        'suspended_at' => 'datetime',
    ];
}

public function isSuspended(): bool
{
    return $this->is_suspended;
}

public function suspend(string $reason): void
{
    $this->update([
        'is_suspended' => true,
        'suspension_reason' => $reason,
        'suspended_at' => now(),
    ]);
}

public function restore(): void
{
    $this->update([
        'is_suspended' => false,
        'suspension_reason' => null,
        'suspended_at' => null,
    ]);
}
```

---

## 5️⃣ FALLA 5: RegistrationTest - Usuario no se autentica

### 📍 Código del Test (Línea 10-18)
```php
test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();  // ❌ FALLA - usuario no autenticado
    $response->assertRedirect(route('dashboard', absolute: false));
});
```

### 🐛 Problema
El controlador `RegisteredUserController` requiere campos adicionales que el test NO está enviando:
- `role` (requerido, debe ser 'guide' o 'tourist')
- `profile_photo` (requerido, debe ser una imagen)

### 💡 Causa Raíz
Mira el código del controlador (línea 34-39):
```php
$request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
    'role' => ['required', 'string', 'in:guide,tourist'],  // ⚠️ REQUERIDO
    'profile_photo' => ['required', 'image', 'max:2048'],  // ⚠️ REQUERIDO
]);
```

El test solo envía `name`, `email`, `password`, y `password_confirmation`, por lo que la validación falla y el usuario nunca se crea ni se autentica.

### ✅ Solución

**Opción A:** Actualizar el test para incluir todos los campos requeridos
```php
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('new users can register', function () {
    Storage::fake('public');

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'tourist',
        'profile_photo' => UploadedFile::fake()->image('avatar.jpg'),
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
```

**Opción B:** Hacer los campos opcionales en el controlador (NO RECOMENDADO)
Si quieres que funcione sin estos campos, tendrías que modificar el controlador, pero eso rompe tu lógica de negocio.

---

## 🎯 Resumen de Soluciones Necesarias

### ✅ Correcciones Inmediatas (en los tests)
1. **BookingTest:** Guardar el modelo en BD antes de verificar
2. **RegistrationTest:** Agregar campos `role` y `profile_photo` al test

### 🔧 Cambios en Base de Datos (migraciones)
3. **Experiences:** Crear migración para agregar `status` e `is_featured`
4. **Users:** Crear migración para agregar campos de suspensión

### 💻 Cambios en Modelos (código)
5. **Experience:** Agregar scopes `published()`, `featured()`, `publiclyVisible()`
6. **User:** Agregar métodos `suspend()`, `restore()`, `isSuspended()`

### 🗑️ Opción Alternativa
- Eliminar los tests que prueban funcionalidad no implementada
- Implementar estas funcionalidades cuando sean necesarias

---

## 📝 Orden Recomendado de Implementación

### Para soluciones rápidas (hacer pasar los tests ahora):
```bash
# 1. Corregir RegistrationTest y BookingTest (archivos de test)
# 2. Comentar o eliminar los tests de funcionalidad no implementada
```

### Para implementación completa (agregar las funcionalidades):
```bash
# 1. Crear migraciones para experiences (status, is_featured)
php artisan make:migration add_status_and_featured_to_experiences_table

# 2. Crear migraciones para users (suspensión)
php artisan make:migration add_suspension_fields_to_users_table

# 3. Ejecutar migraciones
php artisan migrate

# 4. Actualizar modelo Experience con scopes
# 5. Actualizar modelo User con métodos de suspensión
# 6. Actualizar factories para incluir nuevas columnas
# 7. Ejecutar tests
php artisan test
```

---

## 🚀 Siguiente Paso

¿Quieres que:
1. **Corrija los tests ahora** para que pasen (solución rápida)?
2. **Implemente las funcionalidades completas** (migraciones + modelos + tests)?
3. **Elimine los tests** de funcionalidades no implementadas?

Recomienda la **Opción 2** para tener un sistema más robusto y completo.

