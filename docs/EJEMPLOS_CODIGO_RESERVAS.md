# Ejemplos de Código - Sistema de Reservas

Esta guía contiene ejemplos prácticos de código para trabajar con el sistema de reservas de NexLocal.

---

## 📝 Tabla de Contenidos

1. [Crear una Reserva (Controlador)](#crear-una-reserva)
2. [Validar Disponibilidad](#validar-disponibilidad)
3. [Cambiar Estado de Reserva](#cambiar-estado)
4. [Implementar Notificaciones](#notificaciones)
5. [Tests Automatizados](#tests)
6. [Componentes Blade](#componentes-blade)
7. [JavaScript/Alpine.js](#javascript)

---

## 🎯 Crear una Reserva

### Controlador con Transacción Segura

```php
// app/Http/Controllers/BookingController.php

public function store(Request $request)
{
    $validated = $request->validate([
        'availability_slot_id' => 'required|exists:availability_slots,id',
        'num_travelers' => 'required|integer|min:1|max:20',
    ]);

    // Prevenir que guías reserven
    if (auth()->user()->role === 'guide') {
        return back()->with('error', 'Los guías no pueden reservar experiencias.');
    }

    try {
        DB::beginTransaction();

        // Bloqueo pesimista para evitar overbooking
        $slot = AvailabilitySlot::with('experience')
            ->lockForUpdate()
            ->findOrFail($validated['availability_slot_id']);

        // Validar disponibilidad
        if ($validated['num_travelers'] > $slot->available_spots) {
            throw ValidationException::withMessages([
                'num_travelers' => "Solo hay {$slot->available_spots} cupos disponibles."
            ]);
        }

        // Validar que la fecha no haya pasado
        if ($slot->start_time < now()) {
            throw ValidationException::withMessages([
                'availability_slot_id' => 'No puedes reservar para una fecha pasada.'
            ]);
        }

        // Verificar reserva duplicada
        $duplicate = Booking::where('user_id', auth()->id())
            ->where('availability_slot_id', $slot->id)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'availability_slot_id' => 'Ya tienes una reserva activa para este horario.'
            ]);
        }

        // Calcular monto
        $totalAmount = $slot->experience->price * $validated['num_travelers'];

        // Simular pago (en producción: integrar Stripe/PayPal)
        $paymentResult = $this->processPayment($totalAmount);

        if (!$paymentResult['success']) {
            throw new \Exception('Error al procesar el pago: ' . $paymentResult['error']);
        }

        // Crear reserva
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'experience_id' => $slot->experience_id,
            'availability_slot_id' => $slot->id,
            'num_travelers' => $validated['num_travelers'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_intent_id' => $paymentResult['payment_intent_id'],
            'payment_status' => 'succeeded',
            'paid_at' => now(),
        ]);

        // Decrementar cupos
        $slot->decrement('available_spots', $validated['num_travelers']);

        // Log de auditoría
        Log::info('Booking created', [
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
            'experience_id' => $slot->experience_id,
            'num_travelers' => $validated['num_travelers'],
        ]);

        // Notificar al guía
        $slot->experience->user->notify(new NewBookingReceived($booking));

        DB::commit();

        return redirect()->route('bookings.index')
            ->with('success', '¡Reserva realizada con éxito! Esperando confirmación del guía.');

    } catch (ValidationException $e) {
        DB::rollBack();
        throw $e;
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Booking creation failed', [
            'user_id' => auth()->id(),
            'error' => $e->getMessage(),
        ]);
        return back()->with('error', 'Hubo un problema al procesar tu reserva. Intenta de nuevo.');
    }
}

/**
 * Simular procesamiento de pago
 */
private function processPayment($amount)
{
    // En producción: integrar con Stripe, PayPal, etc.
    return [
        'success' => true,
        'payment_intent_id' => 'pi_' . uniqid(),
        'amount' => $amount,
    ];
}
```

---

## ✅ Validar Disponibilidad

### Método Helper en el Modelo

```php
// app/Models/AvailabilitySlot.php

/**
 * Verifica si hay cupos suficientes disponibles
 */
public function hasAvailableSpots($numTravelers)
{
    return $this->available_spots >= $numTravelers;
}

/**
 * Verifica si el slot está en el futuro
 */
public function isFuture()
{
    return $this->start_time > now();
}

/**
 * Verifica si el slot es reservable
 */
public function isBookable($numTravelers)
{
    return $this->isFuture() && 
           $this->hasAvailableSpots($numTravelers) &&
           $this->experience->is_active; // Si tienes este campo
}

/**
 * Scope para slots reservables
 */
public function scopeBookable($query)
{
    return $query->where('start_time', '>', now())
                 ->where('available_spots', '>', 0);
}
```

### Uso en el Controlador

```php
// Validar antes de mostrar el formulario
$slot = AvailabilitySlot::findOrFail($slotId);

if (!$slot->isBookable($numTravelers)) {
    abort(400, 'Este horario ya no está disponible.');
}
```

---

## 🔄 Cambiar Estado de Reserva

### Máquina de Estados Simplificada

```php
// app/Services/BookingStateMachine.php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingStateMachine
{
    protected $booking;
    protected $user;

    public function __construct(Booking $booking, $user)
    {
        $this->booking = $booking;
        $this->user = $user;
    }

    /**
     * Transiciones permitidas
     */
    protected $transitions = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['in_progress', 'cancelled'],
        'in_progress' => ['completed', 'cancelled'],
        'completed' => [],
        'cancelled' => [],
    ];

    /**
     * Intentar transición a nuevo estado
     */
    public function transitionTo($newStatus)
    {
        // Validar que la transición sea válida
        if (!$this->canTransitionTo($newStatus)) {
            throw new \Exception("Transición no permitida: {$this->booking->status} → {$newStatus}");
        }

        // Validar permisos
        if (!$this->hasPermission($newStatus)) {
            throw new \Exception("No tienes permiso para esta transición.");
        }

        DB::beginTransaction();
        try {
            $oldStatus = $this->booking->status;

            // Ejecutar lógica antes del cambio
            $this->beforeTransition($newStatus);

            // Cambiar estado
            $this->booking->status = $newStatus;
            $this->booking->save();

            // Ejecutar lógica después del cambio
            $this->afterTransition($oldStatus, $newStatus);

            // Log
            Log::info('Booking status changed', [
                'booking_id' => $this->booking->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => $this->user->id,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Verifica si la transición es válida
     */
    public function canTransitionTo($newStatus)
    {
        $currentStatus = $this->booking->status;
        return in_array($newStatus, $this->transitions[$currentStatus] ?? []);
    }

    /**
     * Verifica permisos del usuario
     */
    protected function hasPermission($newStatus)
    {
        $isGuide = $this->user->id === $this->booking->experience->user_id;
        $isTourist = $this->user->id === $this->booking->user_id;

        switch ($newStatus) {
            case 'confirmed':
            case 'in_progress':
                return $isGuide;
            
            case 'cancelled':
            case 'completed':
                return $isGuide || $isTourist;
            
            default:
                return false;
        }
    }

    /**
     * Lógica antes de cambiar estado
     */
    protected function beforeTransition($newStatus)
    {
        switch ($newStatus) {
            case 'cancelled':
                // Devolver cupos
                if ($this->booking->availabilitySlot && $this->booking->num_travelers > 0) {
                    $this->booking->availabilitySlot->increment(
                        'available_spots', 
                        $this->booking->num_travelers
                    );
                }
                break;
        }
    }

    /**
     * Lógica después de cambiar estado
     */
    protected function afterTransition($oldStatus, $newStatus)
    {
        // Notificaciones
        switch ($newStatus) {
            case 'confirmed':
                $this->booking->user->notify(new BookingConfirmed($this->booking));
                break;
            
            case 'cancelled':
                if ($this->user->id === $this->booking->experience->user_id) {
                    // Guía canceló
                    $this->booking->user->notify(new BookingCancelledByGuide($this->booking));
                } else {
                    // Turista canceló
                    $this->booking->experience->user->notify(new BookingCancelledByTourist($this->booking));
                }
                break;
            
            case 'completed':
                $this->booking->user->notify(new BookingCompleted($this->booking));
                break;
        }
    }
}
```

### Uso en el Controlador

```php
// app/Http/Controllers/BookingController.php

use App\Services\BookingStateMachine;

public function updateStatus(Request $request, Booking $booking)
{
    $validated = $request->validate([
        'status' => 'required|string|in:confirmed,cancelled,in_progress,completed',
    ]);

    try {
        $stateMachine = new BookingStateMachine($booking, auth()->user());
        $stateMachine->transitionTo($validated['status']);

        return back()->with('success', 'Estado actualizado correctamente.');

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}
```

---

## 🔔 Implementar Notificaciones

### Crear Notificación

```bash
php artisan make:notification BookingConfirmed
```

```php
// app/Notifications/BookingConfirmed.php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingConfirmed extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Canales de notificación
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Email
     */
    public function toMail($notifiable)
    {
        $slot = $this->booking->availabilitySlot;
        
        return (new MailMessage)
            ->subject('¡Tu reserva ha sido confirmada!')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tu reserva para "' . $this->booking->experience->title . '" ha sido confirmada.')
            ->line('📅 Fecha: ' . $slot->start_time->format('d/m/Y H:i'))
            ->line('👥 Viajeros: ' . $this->booking->num_travelers)
            ->line('💰 Total: $' . number_format($this->booking->total_amount, 0) . ' COP')
            ->action('Ver Detalles', route('bookings.index'))
            ->line('¡Nos vemos pronto!');
    }

    /**
     * Base de datos
     */
    public function toArray($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'experience_title' => $this->booking->experience->title,
            'status' => 'confirmed',
            'message' => 'Tu reserva ha sido confirmada',
        ];
    }
}
```

### Mostrar Notificaciones en la UI

```blade
<!-- resources/views/layouts/navigation.blade.php -->

<div class="relative">
    <button @click="showNotifications = !showNotifications" class="relative">
        <svg class="w-6 h-6"><!-- Icono campana --></svg>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>

    <!-- Dropdown de notificaciones -->
    <div x-show="showNotifications" @click.away="showNotifications = false" 
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg">
        <div class="p-4 border-b">
            <h3 class="font-semibold">Notificaciones</h3>
        </div>
        <div class="max-h-96 overflow-y-auto">
            @forelse(auth()->user()->unreadNotifications as $notification)
                <a href="{{ route('bookings.index') }}" 
                   class="block p-4 hover:bg-gray-50 border-b">
                    <p class="text-sm font-medium">{{ $notification->data['message'] }}</p>
                    <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <p class="p-4 text-sm text-gray-500">No tienes notificaciones nuevas</p>
            @endforelse
        </div>
    </div>
</div>
```

---

## 🧪 Tests Automatizados

### Test de Creación de Reserva

```php
// tests/Feature/BookingTest.php

use App\Models\User;
use App\Models\Experience;
use App\Models\AvailabilitySlot;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function turista_puede_crear_reserva_exitosamente()
    {
        // Arrange
        $guide = User::factory()->create(['role' => 'guide']);
        $tourist = User::factory()->create(['role' => 'tourist']);
        
        $experience = Experience::factory()->create([
            'user_id' => $guide->id,
            'price' => 50000,
        ]);
        
        $slot = AvailabilitySlot::factory()->create([
            'experience_id' => $experience->id,
            'available_spots' => 5,
            'start_time' => now()->addDays(1),
        ]);

        // Act
        $response = $this->actingAs($tourist)->post('/bookings', [
            'availability_slot_id' => $slot->id,
            'num_travelers' => 2,
        ]);

        // Assert
        $response->assertRedirect('/bookings');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('bookings', [
            'user_id' => $tourist->id,
            'experience_id' => $experience->id,
            'num_travelers' => 2,
            'status' => 'pending',
            'total_amount' => 100000,
        ]);
        
        $this->assertEquals(3, $slot->fresh()->available_spots);
    }

    /** @test */
    public function no_permite_reservar_sin_cupos_suficientes()
    {
        $tourist = User::factory()->create(['role' => 'tourist']);
        $slot = AvailabilitySlot::factory()->create([
            'available_spots' => 1,
        ]);

        $response = $this->actingAs($tourist)->post('/bookings', [
            'availability_slot_id' => $slot->id,
            'num_travelers' => 2,
        ]);

        $response->assertSessionHasErrors('num_travelers');
        $this->assertDatabaseCount('bookings', 0);
    }

    /** @test */
    public function guia_puede_confirmar_reserva_pendiente()
    {
        $guide = User::factory()->create(['role' => 'guide']);
        $tourist = User::factory()->create(['role' => 'tourist']);
        
        $experience = Experience::factory()->create(['user_id' => $guide->id]);
        $booking = Booking::factory()->create([
            'experience_id' => $experience->id,
            'user_id' => $tourist->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($guide)->patch("/bookings/{$booking->id}/status", [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect();
        $this->assertEquals('confirmed', $booking->fresh()->status);
    }

    /** @test */
    public function turista_no_puede_confirmar_su_propia_reserva()
    {
        $tourist = User::factory()->create(['role' => 'tourist']);
        $booking = Booking::factory()->create([
            'user_id' => $tourist->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($tourist)->patch("/bookings/{$booking->id}/status", [
            'status' => 'confirmed',
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function completar_requiere_confirmacion_de_ambas_partes()
    {
        $guide = User::factory()->create(['role' => 'guide']);
        $tourist = User::factory()->create(['role' => 'tourist']);
        
        $experience = Experience::factory()->create(['user_id' => $guide->id]);
        $booking = Booking::factory()->create([
            'experience_id' => $experience->id,
            'user_id' => $tourist->id,
            'status' => 'in_progress',
        ]);

        // Turista confirma primero
        $this->actingAs($tourist)->patch("/bookings/{$booking->id}/status", [
            'status' => 'completed',
        ]);

        $booking->refresh();
        $this->assertTrue($booking->tourist_confirmed_completed);
        $this->assertEquals('in_progress', $booking->status); // Aún en progreso

        // Guía confirma después
        $this->actingAs($guide)->patch("/bookings/{$booking->id}/status", [
            'status' => 'completed',
        ]);

        $booking->refresh();
        $this->assertTrue($booking->guide_confirmed_completed);
        $this->assertEquals('completed', $booking->status); // Ahora completada
    }

    /** @test */
    public function cancelar_devuelve_cupos_al_slot()
    {
        $tourist = User::factory()->create(['role' => 'tourist']);
        $slot = AvailabilitySlot::factory()->create(['available_spots' => 5]);
        
        $booking = Booking::factory()->create([
            'user_id' => $tourist->id,
            'availability_slot_id' => $slot->id,
            'num_travelers' => 3,
            'status' => 'confirmed',
        ]);

        $this->actingAs($tourist)->patch("/bookings/{$booking->id}/status", [
            'status' => 'cancelled',
        ]);

        $this->assertEquals(8, $slot->fresh()->available_spots);
        $this->assertEquals('cancelled', $booking->fresh()->status);
    }
}
```

---

## 🎨 Componentes Blade

### Component de Status Badge

```php
// app/View/Components/BookingStatusBadge.php

namespace App\View\Components;

use Illuminate\View\Component;

class BookingStatusBadge extends Component
{
    public $status;
    public $text;
    public $color;

    public function __construct($status)
    {
        $this->status = $status;
        
        $config = [
            'pending' => ['text' => 'Pendiente', 'color' => 'yellow'],
            'confirmed' => ['text' => 'Confirmada', 'color' => 'green'],
            'in_progress' => ['text' => 'En Curso', 'color' => 'blue'],
            'completed' => ['text' => 'Completada', 'color' => 'emerald'],
            'cancelled' => ['text' => 'Cancelada', 'color' => 'red'],
        ];

        $this->text = $config[$status]['text'] ?? 'Desconocido';
        $this->color = $config[$status]['color'] ?? 'gray';
    }

    public function render()
    {
        return view('components.booking-status-badge');
    }
}
```

```blade
<!-- resources/views/components/booking-status-badge.blade.php -->

<span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full 
             bg-{{ $color }}-100 text-{{ $color }}-800 border border-{{ $color }}-200">
    @if($status === 'pending')
        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
        </svg>
    @elseif($status === 'confirmed')
        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
    @endif
    {{ $text }}
</span>
```

### Uso

```blade
<x-booking-status-badge :status="$booking->status" />
```

---

## ⚡ JavaScript/Alpine.js

### Confirmación antes de cancelar

```blade
<div x-data="{ showCancelModal: false }">
    <!-- Botón cancelar -->
    <button @click="showCancelModal = true" class="btn-cancel">
        Cancelar Reserva
    </button>

    <!-- Modal de confirmación -->
    <div x-show="showCancelModal" 
         x-cloak
         @click.away="showCancelModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg p-6 max-w-md">
            <h3 class="text-lg font-semibold mb-4">¿Cancelar reserva?</h3>
            <p class="text-gray-600 mb-6">
                Esta acción no se puede deshacer. Se devolverán los cupos 
                y se procesará el reembolso según nuestras políticas.
            </p>
            <div class="flex gap-3 justify-end">
                <button @click="showCancelModal = false" class="btn-secondary">
                    No, mantener
                </button>
                <form action="{{ route('bookings.status', $booking) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="btn-danger">
                        Sí, cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
```

### Loading state en botones

```blade
<form action="{{ route('bookings.status', $booking) }}" method="POST" 
      x-data="{ loading: false }" 
      @submit="loading = true">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="confirmed">
    
    <button type="submit" 
            :disabled="loading"
            :class="{ 'opacity-50 cursor-not-allowed': loading }"
            class="btn-primary">
        <span x-show="!loading">Confirmar Reserva</span>
        <span x-show="loading" class="flex items-center">
            <svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Procesando...
        </span>
    </button>
</form>
```

---

## 📊 Query Optimization

### Eager Loading para evitar N+1

```php
// ❌ MAL - Query N+1
$bookings = Booking::where('user_id', auth()->id())->get();
foreach ($bookings as $booking) {
    echo $booking->experience->title; // Query adicional por cada booking
    echo $booking->availabilitySlot->start_time; // Otro query
}

// ✅ BIEN - Eager loading
$bookings = Booking::where('user_id', auth()->id())
    ->with(['experience', 'availabilitySlot', 'review'])
    ->get();
```

### Scope para queries comunes

```php
// app/Models/Booking.php

public function scopeActive($query)
{
    return $query->whereIn('status', ['pending', 'confirmed', 'in_progress']);
}

public function scopeForUser($query, $userId)
{
    return $query->where('user_id', $userId);
}

public function scopeUpcoming($query)
{
    return $query->whereHas('availabilitySlot', function($q) {
        $q->where('start_time', '>', now());
    });
}

// Uso
$activeBookings = Booking::forUser(auth()->id())
    ->active()
    ->upcoming()
    ->with(['experience', 'availabilitySlot'])
    ->get();
```

---

**Última actualización:** Noviembre 2025  
**Para dudas o contribuciones:** Ver `docs/MANUAL_ESTADOS_RESERVAS.md`

