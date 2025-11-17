# Manual de Estados de Reservas - NexLocal

**Versión:** 1.0  
**Fecha:** Noviembre 2025  
**Sistema:** NexLocal - Plataforma de Experiencias Turísticas

---

## 📋 Índice

1. [Introducción](#introducción)
2. [Estados de Reserva](#estados-de-reserva)
3. [Flujo Completo de una Reserva](#flujo-completo-de-una-reserva)
4. [Vista del Turista](#vista-del-turista)
5. [Vista del Guía](#vista-del-guía)
6. [Reglas y Permisos](#reglas-y-permisos)
7. [Gestión de Cupos](#gestión-de-cupos)
8. [Ejemplos Prácticos](#ejemplos-prácticos)
9. [Casos Especiales](#casos-especiales)
10. [Recomendaciones Técnicas](#recomendaciones-técnicas)

---

## 🎯 Introducción

Este manual explica cómo funcionan los estados de las reservas en NexLocal, desde que un turista reserva una experiencia hasta que ésta se completa o cancela. El sistema involucra dos actores principales:

- **Turista**: Usuario que reserva experiencias
- **Guía**: Usuario que ofrece y gestiona experiencias

---

## 📊 Estados de Reserva

El sistema maneja **5 estados principales** para cada reserva:

| Estado | Descripción | Color Sugerido |
|--------|-------------|----------------|
| `pending` | Reserva creada y pagada, esperando confirmación del guía | 🟡 Amarillo |
| `confirmed` | El guía ha confirmado la reserva | 🟢 Verde claro |
| `in_progress` | La experiencia está en curso | 🔵 Azul |
| `completed` | Experiencia finalizada (requiere confirmación de ambas partes) | ✅ Verde |
| `cancelled` | Reserva cancelada por turista o guía | 🔴 Rojo |

### Campos Adicionales

- `payment_status`: Estado del pago (`succeeded`, `pending`, `failed`)
- `tourist_confirmed_completed`: Boolean - El turista confirmó finalización
- `guide_confirmed_completed`: Boolean - El guía confirmó finalización
- `num_travelers`: Número de personas en la reserva
- `total_amount`: Monto total pagado

---

## 🔄 Flujo Completo de una Reserva

```
┌─────────────┐
│   TURISTA   │
│   RESERVA   │
└──────┬──────┘
       │
       ▼
  ┌─────────┐
  │ PENDING │ ◄── Reserva creada, pago procesado
  └────┬────┘     Cupos decrementados
       │
       │ (Guía confirma)
       ▼
  ┌───────────┐
  │ CONFIRMED │ ◄── Guía acepta la reserva
  └─────┬─────┘
        │
        │ (Guía inicia)
        ▼
  ┌─────────────┐
  │ IN_PROGRESS │ ◄── Experiencia en curso
  └──────┬──────┘
         │
         │ (Ambos confirman finalización)
         ▼
  ┌───────────┐
  │ COMPLETED │ ◄── Experiencia finalizada
  └───────────┘     Reseña habilitada

  ┌───────────┐
  │ CANCELLED │ ◄── Puede ocurrir desde cualquier estado
  └───────────┘     (excepto COMPLETED)
                    Cupos devueltos
```

---

## 👤 Vista del Turista

### 1️⃣ Crear una Reserva

**Página:** Detalle de Experiencia  
**Acción:** Seleccionar fecha, número de viajeros y confirmar pago  
**Ruta:** `POST /bookings`

**Parámetros:**
- `availability_slot_id`: ID del horario seleccionado
- `num_travelers`: Cantidad de personas (mín. 1)

**Resultado:**
- Estado inicial: `pending`
- Mensaje: *"¡Reserva realizada con éxito! Esperando confirmación del guía."*
- Se decrementa `available_spots` del slot

**Validaciones:**
- Debe haber cupos suficientes
- Los guías no pueden reservar experiencias
- El pago debe procesarse correctamente

---

### 2️⃣ Ver Mis Reservas

**Página:** `/bookings`  
**Muestra:** Lista de todas las reservas del turista ordenadas por fecha

**Información visible por estado:**

#### Estado `PENDING` 🟡
- **Mostrar:**
  - Badge: "Pendiente de confirmación"
  - Fecha y hora de la experiencia
  - Número de viajeros
  - Monto pagado
  - Botón: **"Cancelar Reserva"**
- **Acciones disponibles:**
  - ✅ Cancelar → Cambia a `cancelled`, devuelve cupos

#### Estado `CONFIRMED` 🟢
- **Mostrar:**
  - Badge: "Confirmada"
  - Detalles de la experiencia
  - Punto de encuentro (si está definido)
  - Botón: **"Cancelar Reserva"** (si aplica política)
- **Acciones disponibles:**
  - ✅ Cancelar (si políticas lo permiten)
  - Ver detalles del guía y contacto

#### Estado `IN_PROGRESS` 🔵
- **Mostrar:**
  - Badge: "En curso"
  - Botón: **"Marcar como Completada"** (si aún no confirmó)
  - Mensaje: "Esperando que ambas partes confirmen finalización"
- **Acciones disponibles:**
  - ✅ Confirmar finalización → Marca `tourist_confirmed_completed = true`
  - Si el guía ya confirmó, la reserva pasa automáticamente a `completed`

#### Estado `COMPLETED` ✅
- **Mostrar:**
  - Badge: "Completada"
  - Fecha de finalización
  - Botón: **"Escribir Reseña"** (si no la ha escrito)
  - Si ya escribió reseña: mostrarla
- **Acciones disponibles:**
  - ✅ Crear reseña (1 vez)
  - Ver reseña escrita

#### Estado `CANCELLED` 🔴
- **Mostrar:**
  - Badge: "Cancelada"
  - Fecha de cancelación
  - Razón (si existe)
  - Estado de reembolso
- **Acciones disponibles:**
  - Ninguna (estado final)

---

### 3️⃣ Cancelar una Reserva

**Ruta:** `PATCH /bookings/{booking}/status`  
**Parámetro:** `status=cancelled`

**Condiciones:**
- Solo si el estado NO es `completed`
- Se devuelven los cupos al slot
- Se procesa reembolso según políticas

**Resultado:**
- Estado: `cancelled`
- Mensaje: *"Reserva cancelada. Se han devuelto los cupos."*

---

### 4️⃣ Confirmar Finalización

**Ruta:** `PATCH /bookings/{booking}/status`  
**Parámetro:** `status=completed`

**Condiciones:**
- Solo desde estado `in_progress`
- Marca `tourist_confirmed_completed = true`
- Si `guide_confirmed_completed` también es `true`, el estado pasa a `completed`

**Resultado:**
- Mensaje: *"Has marcado la experiencia como completada."*
- Si ambos confirmaron: *"¡Experiencia completada! Ya puedes escribir una reseña."*

---

## 🎯 Vista del Guía

### 1️⃣ Panel de Reservas Recibidas

**Página:** Dashboard del Guía  
**Muestra:** Lista de reservas para sus experiencias

**Información visible:**
- Nombre del turista
- Experiencia reservada
- Fecha y hora
- Número de viajeros
- Estado actual
- Monto

---

### 2️⃣ Confirmar una Reserva

**Estado requerido:** `pending`  
**Ruta:** `PATCH /bookings/{booking}/status`  
**Parámetro:** `status=confirmed`

**Acción en el dashboard:**
- Botón: **"Confirmar Reserva"**

**Resultado:**
- Estado cambia a `confirmed`
- Se envía notificación al turista
- Mensaje: *"Reserva confirmada correctamente."*

**HTML de ejemplo:**
```html
<form action="{{ route('bookings.status', $booking) }}" method="POST">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="confirmed">
    <button type="submit" class="btn-confirm">Confirmar</button>
</form>
```

---

### 3️⃣ Iniciar la Experiencia

**Estado requerido:** `confirmed`  
**Ruta:** `PATCH /bookings/{booking}/status`  
**Parámetro:** `status=in_progress`

**Acción en el dashboard:**
- Botón: **"Iniciar Experiencia"** (disponible en la fecha/hora de la experiencia)

**Resultado:**
- Estado cambia a `in_progress`
- Mensaje: *"Experiencia marcada como 'En Curso'."*

---

### 4️⃣ Confirmar Finalización

**Estado requerido:** `in_progress`  
**Ruta:** `PATCH /bookings/{booking}/status`  
**Parámetro:** `status=completed`

**Acción en el dashboard:**
- Botón: **"Marcar como Finalizada"**

**Resultado:**
- Marca `guide_confirmed_completed = true`
- Si `tourist_confirmed_completed` también es `true`, el estado pasa a `completed`
- Mensaje: *"Has marcado la experiencia como completada."*

---

### 5️⃣ Cancelar una Reserva

**Ruta:** `PATCH /bookings/{booking}/status`  
**Parámetro:** `status=cancelled`

**Condiciones:**
- Solo si el estado NO es `completed`
- Requiere justificación (opcional pero recomendado)

**Resultado:**
- Estado: `cancelled`
- Cupos devueltos
- Notificación al turista
- Proceso de reembolso iniciado

---

## 🔒 Reglas y Permisos

### Tabla de Permisos por Acción

| Acción | Turista | Guía | Estado Requerido |
|--------|---------|------|------------------|
| Crear reserva | ✅ | ❌ | - |
| Confirmar | ❌ | ✅ | `pending` |
| Iniciar | ❌ | ✅ | `confirmed` |
| Marcar completada | ✅ | ✅ | `in_progress` |
| Cancelar | ✅ | ✅ | Cualquiera excepto `completed` |

### Validaciones de Seguridad

1. **Autenticación:** Todas las rutas requieren middleware `auth`
2. **Autorización:** 
   - El turista solo puede gestionar sus propias reservas
   - El guía solo puede gestionar reservas de sus experiencias
3. **Estado válido:** Cada transición valida el estado actual
4. **Cupos:** Se valida disponibilidad antes de reservar

**Código de validación (extracto):**
```php
// Verificar si el usuario es el turista de la reserva
$isTourist = $user->id === $booking->user_id;

// Verificar si el usuario es el guía de la experiencia
$isGuide = $user->id === $booking->experience->user_id;

if (!$isTourist && !$isGuide) {
    abort(403, 'No tienes permiso para esta acción.');
}
```

---

## 📦 Gestión de Cupos

### Al Crear Reserva
```php
// Se decrementa available_spots del slot
$slot->decrement('available_spots', $request->num_travelers);
```

**Ejemplo:**
- Slot inicial: `available_spots = 10`
- Reserva de 3 viajeros
- Resultado: `available_spots = 7`

### Al Cancelar
```php
// Se devuelven los cupos
if ($booking->availabilitySlot && $booking->num_travelers > 0) {
    $booking->availabilitySlot->increment('available_spots', $booking->num_travelers);
}
```

**Ejemplo:**
- Slot actual: `available_spots = 7`
- Cancelación de reserva de 3 viajeros
- Resultado: `available_spots = 10`

### ⚠️ Problema de Concurrencia (Overbooking)

**Escenario:**
1. Slot tiene 2 cupos disponibles
2. Dos turistas intentan reservar 2 viajeros simultáneamente
3. Ambas peticiones leen `available_spots = 2`
4. Ambas reservas se crean (overbooking: -2 cupos)

**Solución Recomendada:**
```php
DB::transaction(function () use ($slotId, $numTravelers) {
    // Bloqueo pesimista
    $slot = AvailabilitySlot::lockForUpdate()->find($slotId);
    
    if ($slot->available_spots < $numTravelers) {
        throw ValidationException::withMessages([
            'num_travelers' => 'No hay suficientes cupos disponibles.'
        ]);
    }
    
    $slot->decrement('available_spots', $numTravelers);
    
    Booking::create([
        // ... datos de la reserva
    ]);
});
```

---

## 💡 Ejemplos Prácticos

### Ejemplo 1: Flujo Completo Normal

**Turista: Ana** reserva experiencia "Tour por el Río Sinú" del **Guía: Carlos**

1. **Ana reserva** (12:00 PM)
   - Estado: `pending`
   - Cupos: 10 → 8 (reservó para 2 personas)
   - Pago: $100,000 COP

2. **Carlos confirma** (12:30 PM)
   - Estado: `pending` → `confirmed`
   - Notificación enviada a Ana

3. **Carlos inicia la experiencia** (9:00 AM día siguiente)
   - Estado: `confirmed` → `in_progress`

4. **Carlos marca finalización** (12:00 PM)
   - `guide_confirmed_completed = true`
   - Estado sigue en `in_progress`

5. **Ana marca finalización** (12:15 PM)
   - `tourist_confirmed_completed = true`
   - Estado: `in_progress` → `completed`
   - Ana puede escribir reseña

---

### Ejemplo 2: Cancelación por el Turista

**Turista: Juan** reserva pero cancela antes de la fecha

1. **Juan reserva** 
   - Estado: `pending`
   - Cupos: 5 → 3 (2 viajeros)

2. **Guía confirma**
   - Estado: `confirmed`

3. **Juan cancela** (3 días antes)
   - Estado: `confirmed` → `cancelled`
   - Cupos: 3 → 5 (devueltos)
   - Reembolso: 80% (según política)

---

### Ejemplo 3: Cancelación por el Guía

**Guía: María** cancela por mal tiempo

1. **Turista reserva y guía confirma**
   - Estado: `confirmed`

2. **María cancela** (por emergencia)
   - Estado: `confirmed` → `cancelled`
   - Cupos devueltos
   - Reembolso: 100%
   - Notificación urgente al turista

---

## ⚠️ Casos Especiales

### 1. Confirmación Parcial de Finalización

**Escenario:** Solo una parte confirma finalización

- Si turista confirma primero:
  - `tourist_confirmed_completed = true`
  - Estado permanece `in_progress`
  - Mensaje al guía: "El turista ha confirmado finalización"

- Si guía confirma primero:
  - `guide_confirmed_completed = true`
  - Estado permanece `in_progress`
  - Mensaje al turista: "El guía ha confirmado finalización"

- Cuando ambos confirman:
  - Estado cambia a `completed`
  - Se habilita escritura de reseña

---

### 2. Reserva sin Cupos Suficientes

```php
// Validación en BookingController@store
if ($request->num_travelers > $slot->available_spots) {
    throw ValidationException::withMessages([
        'num_travelers' => 'No hay suficientes cupos disponibles. 
                           Cupos restantes: ' . $slot->available_spots
    ]);
}
```

**Mensaje al usuario:**
> ❌ No hay suficientes cupos disponibles. Cupos restantes: 2

---

### 3. Intento de Acción No Permitida

**Ejemplo:** Turista intenta confirmar una reserva (acción solo para guías)

```php
// En BookingController@updateStatus
if ($newStatus === 'confirmed' && !$isGuide) {
    abort(403, 'Solo el guía puede confirmar reservas.');
}
```

**Resultado:** Error 403 Forbidden

---

### 4. Cancelación de Reserva Completada

```php
if ($newStatus === 'cancelled' && $booking->status === 'completed') {
    return back()->with('error', 
        'No se puede cancelar una reserva ya completada.'
    );
}
```

---

## 🛠️ Recomendaciones Técnicas

### 1. Implementar Transacciones

```php
// ✅ CORRECTO
DB::transaction(function () {
    $slot = AvailabilitySlot::lockForUpdate()->find($slotId);
    $slot->decrement('available_spots', $num);
    Booking::create([...]);
});

// ❌ INCORRECTO (condición de carrera)
$slot->decrement('available_spots', $num);
Booking::create([...]);
```

### 2. Notificaciones

**Eventos a notificar:**
- Nueva reserva → Notificar al guía
- Reserva confirmada → Notificar al turista
- Reserva cancelada → Notificar a ambas partes
- Experiencia completada → Recordatorio de reseña

**Implementación sugerida:**
```php
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingConfirmed;

// Al confirmar
$booking->user->notify(new BookingConfirmed($booking));
```

### 3. Logs de Auditoría

Registrar cambios de estado:

```php
use Illuminate\Support\Facades\Log;

Log::info('Booking status changed', [
    'booking_id' => $booking->id,
    'old_status' => $booking->getOriginal('status'),
    'new_status' => $booking->status,
    'changed_by' => auth()->id(),
    'timestamp' => now(),
]);
```

### 4. Validaciones Adicionales

```php
// Validar que la fecha no haya pasado
$slot = AvailabilitySlot::find($slotId);
if ($slot->start_time < now()) {
    throw ValidationException::withMessages([
        'availability_slot_id' => 'No puedes reservar para una fecha pasada.'
    ]);
}

// Validar reserva duplicada
$exists = Booking::where('user_id', auth()->id())
    ->where('availability_slot_id', $slotId)
    ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
    ->exists();
    
if ($exists) {
    throw ValidationException::withMessages([
        'availability_slot_id' => 'Ya tienes una reserva activa para este horario.'
    ]);
}
```

### 5. Tests Automatizados

```php
// tests/Feature/BookingTest.php

/** @test */
public function turista_puede_crear_reserva()
{
    $slot = AvailabilitySlot::factory()->create(['available_spots' => 5]);
    $tourist = User::factory()->create(['role' => 'tourist']);
    
    $this->actingAs($tourist)
        ->post('/bookings', [
            'availability_slot_id' => $slot->id,
            'num_travelers' => 2,
        ])
        ->assertRedirect('/bookings')
        ->assertSessionHas('success');
        
    $this->assertEquals(3, $slot->fresh()->available_spots);
}

/** @test */
public function guia_puede_confirmar_reserva_pending()
{
    $booking = Booking::factory()->create(['status' => 'pending']);
    $guide = $booking->experience->user;
    
    $this->actingAs($guide)
        ->patch("/bookings/{$booking->id}/status", ['status' => 'confirmed'])
        ->assertRedirect()
        ->assertSessionHas('success');
        
    $this->assertEquals('confirmed', $booking->fresh()->status);
}

/** @test */
public function no_permite_overbooking()
{
    $slot = AvailabilitySlot::factory()->create(['available_spots' => 1]);
    
    // Primera reserva exitosa
    Booking::factory()->create([
        'availability_slot_id' => $slot->id,
        'num_travelers' => 1,
    ]);
    
    $slot->decrement('available_spots', 1); // Ahora = 0
    
    // Segunda reserva debe fallar
    $tourist = User::factory()->create(['role' => 'tourist']);
    
    $this->actingAs($tourist)
        ->post('/bookings', [
            'availability_slot_id' => $slot->id,
            'num_travelers' => 1,
        ])
        ->assertSessionHasErrors('num_travelers');
}
```

---

## 📚 Recursos del Proyecto

### Archivos Relacionados

| Archivo | Descripción |
|---------|-------------|
| `app/Http/Controllers/BookingController.php` | Controlador principal de reservas |
| `app/Models/Booking.php` | Modelo de reservas |
| `app/Models/AvailabilitySlot.php` | Modelo de horarios disponibles |
| `routes/web.php` | Definición de rutas |
| `resources/views/bookings/index.blade.php` | Vista de reservas del turista |
| `resources/views/dashboard/guide.blade.php` | Panel del guía |

### Rutas API Relacionadas

```php
// Crear reserva
POST /bookings

// Listar mis reservas
GET /bookings

// Cambiar estado de reserva
PATCH /bookings/{booking}/status

// Marcar como completada (helper)
PATCH /bookings/{booking}/mark-completed

// Cancelación por guía
PATCH /bookings/{booking}/guide-cancel
```

---

## 📞 Soporte

Para dudas o reportar problemas con el sistema de reservas:

- **Email:** soporte@nexlocal.com
- **Documentación técnica:** `/docs`
- **Repositorio:** GitHub - NexLocal

---

**Última actualización:** Noviembre 2025  
**Versión del sistema:** 1.0  
**Autor:** Equipo de Desarrollo NexLocal

