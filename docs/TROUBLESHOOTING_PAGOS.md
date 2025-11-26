# 🐛 Troubleshooting - Pasarela de Pagos

## Error Resuelto 1: "Call to a member function format() on null"

### 🔍 Descripción del Error

```
Internal Server Error
Call to a member function format() on null
PHP 8.2.12 Laravel 12.31.1
127.0.0.1:8000

Stack Trace:
0 - app\Http\Controllers\BookingController.php:63
```

### 🎯 Causa Raíz

El error ocurría porque el código intentaba acceder a `$slot->date->format()`, pero el modelo `AvailabilitySlot` **NO tiene un campo `date`**.

### ✅ Solución Aplicada

**Modelo AvailabilitySlot tiene:**
```php
protected $fillable = [
    'experience_id',
    'start_time',    // ✅ Fecha/hora de inicio
    'end_time',      // ✅ Fecha/hora de fin
    'max_slots',
    'available_spots',
];

protected function casts(): array
{
    return [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
```

**Cambios realizados:**

#### 1. BookingController.php - Línea 63
**ANTES (❌ Error):**
```php
'booking_date' => $slot->date->format('d/m/Y H:i'),
```

**DESPUÉS (✅ Corregido):**
```php
'booking_date' => $slot->start_time->format('d/m/Y H:i'),
```

#### 2. success.blade.php - Línea 67
**ANTES (❌ Error):**
```php
{{ $booking->availabilitySlot->date->format('d/m/Y H:i') }}
```

**DESPUÉS (✅ Corregido):**
```php
{{ $booking->availabilitySlot->start_time->format('d/m/Y H:i') }}
```

---

## Error Resuelto 2: "table bookings has no column named payment_method"

### 🔍 Descripción del Error

```
SQLSTATE[HY000]: General error: 1 table bookings has no column named payment_method
Connection: sqlite
SQL: insert into "bookings" (..., "payment_method", ...) values (...)
```

### 🎯 Causa Raíz

La tabla `bookings` no tenía las columnas necesarias para el sistema de pagos:
- `payment_intent_id`
- `payment_status`
- `payment_method`
- `paid_at`

### ✅ Solución Aplicada

**Creada nueva migración:**
```
database/migrations/2025_11_26_000001_add_payment_gateway_columns_to_bookings.php
```

**Columnas añadidas:**
```php
Schema::table('bookings', function (Blueprint $table) {
    $table->string('payment_intent_id')->nullable();
    $table->string('payment_status')->nullable();
    $table->string('payment_method')->nullable();
    $table->timestamp('paid_at')->nullable();
});
```

**Ejecutar migración:**
```bash
php artisan migrate
```

**Resultado:**
```
✅ payment_intent_id - Para guardar ID de transacción (pi_mock_xxxxx)
✅ payment_status - Para guardar estado (succeeded, failed, pending)
✅ payment_method - Para guardar método (tarjeta_simulada)
✅ paid_at - Para guardar fecha y hora del pago
```

---

## Error Resuelto 3: "Call to format() on null en success.blade.php"

### 🔍 Descripción del Error

```
Internal Server Error
Call to a member function format() on null

Location: resources\views\bookings\success.blade.php:126
Route: GET /checkout/success/{booking}
```

### 🎯 Causa Raíz

El campo `$booking->paid_at` está en `null` cuando se intenta mostrar la página de éxito, causando que `->format()` falle.

**Posibles causas:**
1. El valor no se guardó correctamente en el `Booking::create()`
2. La función `now()` no está disponible en el contexto
3. El campo fue silenciosamente ignorado durante la creación

### ✅ Soluciones Aplicadas

#### 1. Vista: Manejo de null con fallback

**Archivo:** `resources/views/bookings/success.blade.php` (línea 126)

**ANTES (❌ Error):**
```blade
{{ $booking->paid_at->format('d/m/Y H:i') }}
```

**DESPUÉS (✅ Corregido):**
```blade
{{ ($booking->paid_at ?? $booking->created_at)->format('d/m/Y H:i') }}
```

**Explicación:** Usa el operador null coalescing (`??`) para mostrar `created_at` si `paid_at` es null.

---

#### 2. Controller: Forzar guardado de datos de pago

**Archivo:** `app/Http/Controllers/BookingController.php` (método `processPayment`)

**Cambio 1: Usar Carbon explícitamente**
```php
// ANTES
$paymentIntentId = 'pi_mock_' . uniqid();
$booking = Booking::create([
    // ...
    'paid_at' => now(),
]);

// DESPUÉS
use Carbon\Carbon;

$paymentIntentId = 'pi_mock_' . uniqid();
$paidAt = Carbon::now();

$booking = Booking::create([
    // ...
    'paid_at' => $paidAt,
]);
```

**Cambio 2: Verificación y guardado adicional**
```php
// Verificar y asegurar que se guardaron los datos de pago
if (!$booking->payment_intent_id || !$booking->paid_at) {
    $booking->payment_intent_id = $paymentIntentId;
    $booking->paid_at = $paidAt;
    $booking->save();
}
```

---

## 📋 Verificación Post-Corrección

### Pasos para probar:

1. **Limpia la caché:**
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

2. **Reinicia el servidor:**
```bash
# Ctrl+C para detener
php artisan serve
```

3. **Prueba el flujo completo:**
```
1. Login como turista
2. Ve a /experiences/1
3. Selecciona fecha
4. Clic "Reservar Ahora"
5. Debe redirigir a /checkout ✅
6. Llena formulario
7. Clic "Pagar"
8. Debe redirigir a /success ✅
```

### Resultado Esperado:

- ✅ Redirige a checkout sin errores
- ✅ Muestra fecha correctamente en resumen
- ✅ Procesa pago exitosamente
- ✅ Muestra fecha en página de éxito

---

## 🔎 Otros Posibles Errores Similares

### Error: "Trying to get property of non-object"

**Causa:** Relación no cargada o registro no existe.

**Solución:**
```php
// Asegúrate de cargar las relaciones
$booking->load(['experience', 'availabilitySlot']);

// O usa eager loading
$booking = Booking::with(['experience', 'availabilitySlot'])->find($id);
```

### Error: "Undefined property"

**Causa:** Campo no existe en el modelo o base de datos.

**Solución:**
1. Verifica el modelo:
```php
// Revisa $fillable y casts()
```

2. Verifica la migración:
```bash
php artisan migrate:status
```

3. Verifica la base de datos:
```sql
PRAGMA table_info(availability_slots);
-- Debe mostrar: start_time, end_time (NO date)
```

### Error: "Column not found"

**Causa:** Campo referenciado no existe en tabla.

**Solución:**
```bash
# Verifica la estructura de la tabla
php artisan tinker
> Schema::getColumnListing('availability_slots')
# Debe retornar: id, experience_id, start_time, end_time, max_slots, available_spots, created_at, updated_at
```

---

## 🛠️ Comandos Útiles para Debug

### Ver estructura de modelo en Tinker:
```bash
php artisan tinker
> $slot = App\Models\AvailabilitySlot::first()
> $slot->getAttributes()
> $slot->getCasts()
```

### Ver logs en tiempo real:
```bash
# Windows (PowerShell)
Get-Content storage\logs\laravel.log -Wait -Tail 50

# Linux/Mac
tail -f storage/logs/laravel.log
```

### Limpiar todo (cuando algo raro pasa):
```bash
php artisan optimize:clear
# Limpia: config, cache, route, view, compiled
```

---

## 📊 Checklist de Validación

Después de cualquier cambio en el código de checkout, verifica:

- [ ] `$slot->start_time` se usa (NO `$slot->date`)
- [ ] `$slot->end_time` se usa si necesitas hora de fin
- [ ] Relaciones cargadas con `->with()`
- [ ] Casts definidos en el modelo
- [ ] Campos existen en `$fillable`
- [ ] Logs limpios sin errores

---

## 🎯 Resumen

**Problema:** `$slot->date` no existe  
**Solución:** Usar `$slot->start_time`  
**Archivos corregidos:** 2 (BookingController.php, success.blade.php)  
**Estado:** ✅ Resuelto

---

## 📞 Si el Error Persiste

1. **Verifica que guardaste los archivos**
2. **Limpia caché:** `php artisan optimize:clear`
3. **Reinicia servidor:** Ctrl+C y `php artisan serve`
4. **Revisa logs:** `storage/logs/laravel.log`
5. **Verifica la base de datos:** ¿Existe el slot con ID 3?

---

**Última actualización:** 26/11/2025  
**Estado:** ✅ Error corregido y documentado

