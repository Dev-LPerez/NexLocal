# Guía Rápida de Estados - NexLocal

## 🎯 Diagrama Visual de Estados

```
                    CREAR RESERVA (Turista)
                            │
                            ▼
                    ┌──────────────┐
                    │   PENDING    │  🟡
                    │              │
                    │ - Pago OK    │
                    │ - Cupos -X   │
                    └──────┬───────┘
                           │
                  ┌────────┴────────┐
                  │                 │
         CONFIRMAR (Guía)      CANCELAR (Ambos)
                  │                 │
                  ▼                 ▼
            ┌──────────┐      ┌──────────┐
            │CONFIRMED │ 🟢   │CANCELLED │ 🔴
            └────┬─────┘      │          │
                 │            │Cupos +X  │
        INICIAR (Guía)        └──────────┘
                 │
                 ▼
          ┌─────────────┐
          │ IN_PROGRESS │ 🔵
          │             │
          │ Experiencia │
          │  en curso   │
          └──────┬──────┘
                 │
     ┌───────────┴───────────┐
     │                       │
COMPLETAR              COMPLETAR
 (Turista)              (Guía)
     │                       │
     └───────────┬───────────┘
                 │
          (Ambos confirmaron)
                 │
                 ▼
           ┌──────────┐
           │COMPLETED │ ✅
           │          │
           │ Reseña   │
           │habilitada│
           └──────────┘
```

---

## 📊 Tabla Rápida de Acciones

| Estado | Turista puede | Guía puede | Siguiente estado |
|--------|---------------|------------|------------------|
| `pending` | ❌ Esperar<br>✅ Cancelar | ✅ Confirmar<br>✅ Cancelar | `confirmed` o `cancelled` |
| `confirmed` | ✅ Ver detalles<br>✅ Cancelar | ✅ Iniciar<br>✅ Cancelar | `in_progress` o `cancelled` |
| `in_progress` | ✅ Marcar completada | ✅ Marcar completada | `completed` (ambos deben confirmar) |
| `completed` | ✅ Escribir reseña | ✅ Ver reseña | (estado final) |
| `cancelled` | ❌ Ninguna | ❌ Ninguna | (estado final) |

---

## 🎨 Badges de Estado (UI)

### Colores Recomendados

```css
/* Tailwind CSS */
.badge-pending {
  @apply bg-yellow-100 text-yellow-800 border border-yellow-200;
}

.badge-confirmed {
  @apply bg-green-100 text-green-800 border border-green-200;
}

.badge-in-progress {
  @apply bg-blue-100 text-blue-800 border border-blue-200;
}

.badge-completed {
  @apply bg-emerald-100 text-emerald-800 border border-emerald-200;
}

.badge-cancelled {
  @apply bg-red-100 text-red-800 border border-red-200;
}
```

### Ejemplo Blade Component

```blade
@php
    $statusConfig = [
        'pending' => ['text' => 'Pendiente', 'color' => 'yellow'],
        'confirmed' => ['text' => 'Confirmada', 'color' => 'green'],
        'in_progress' => ['text' => 'En Curso', 'color' => 'blue'],
        'completed' => ['text' => 'Completada', 'color' => 'emerald'],
        'cancelled' => ['text' => 'Cancelada', 'color' => 'red'],
    ];
    $config = $statusConfig[$booking->status] ?? ['text' => 'Desconocido', 'color' => 'gray'];
@endphp

<span class="px-3 py-1 text-sm font-medium rounded-full bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800 border border-{{ $config['color'] }}-200">
    {{ $config['text'] }}
</span>
```

---

## 🧭 Navegación Rápida

### Para Turistas
1. **Ver experiencias** → `/`
2. **Reservar** → `POST /bookings`
3. **Mis reservas** → `/bookings`
4. **Cancelar** → `PATCH /bookings/{id}/status` (status=cancelled)
5. **Completar** → `PATCH /bookings/{id}/status` (status=completed)

### Para Guías
1. **Dashboard** → `/dashboard`
2. **Confirmar** → `PATCH /bookings/{id}/status` (status=confirmed)
3. **Iniciar** → `PATCH /bookings/{id}/status` (status=in_progress)
4. **Completar** → `PATCH /bookings/{id}/status` (status=completed)
5. **Cancelar** → `PATCH /bookings/{id}/status` (status=cancelled)

---

## ⏱️ Tiempos Típicos

| Transición | Tiempo esperado |
|------------|-----------------|
| `pending` → `confirmed` | < 24 horas |
| `confirmed` → `in_progress` | En fecha/hora programada |
| `in_progress` → `completed` | Duración de experiencia + confirmación |
| Cualquier → `cancelled` | Inmediato |

---

## 🔔 Notificaciones

### Al Turista
- ✉️ Reserva creada (pending)
- ✉️ Reserva confirmada (confirmed)
- ✉️ Experiencia iniciada (in_progress)
- ✉️ Recordatorio de confirmar finalización
- ✉️ Experiencia completada - Escribir reseña
- ⚠️ Reserva cancelada por guía

### Al Guía
- ✉️ Nueva reserva recibida (pending)
- ✉️ Recordatorio: confirmar reserva
- ✉️ Reserva cancelada por turista
- ✉️ Turista confirmó finalización

---

## 📱 Ejemplos de Interfaz

### Card de Reserva (Vista Turista)

```blade
<div class="border rounded-lg p-4 bg-white shadow">
    <!-- Header -->
    <div class="flex justify-between items-start mb-3">
        <h3 class="font-semibold text-lg">{{ $booking->experience->title }}</h3>
        <x-status-badge :status="$booking->status" />
    </div>
    
    <!-- Info -->
    <div class="space-y-2 text-sm text-gray-600 mb-4">
        <p>📅 {{ $booking->availabilitySlot->start_time->format('d/m/Y H:i') }}</p>
        <p>👥 {{ $booking->num_travelers }} viajero(s)</p>
        <p>💰 ${{ number_format($booking->total_amount, 0) }} COP</p>
    </div>
    
    <!-- Actions -->
    <div class="flex gap-2">
        @if($booking->status === 'pending')
            <button class="btn-cancel">Cancelar Reserva</button>
        @elseif($booking->status === 'in_progress' && !$booking->tourist_confirmed_completed)
            <button class="btn-complete">Marcar Completada</button>
        @elseif($booking->status === 'completed' && !$booking->review)
            <a href="{{ route('reviews.create', ['booking_id' => $booking->id]) }}" 
               class="btn-review">Escribir Reseña</a>
        @endif
    </div>
</div>
```

### Card de Reserva (Vista Guía)

```blade
<div class="border rounded-lg p-4 bg-white shadow">
    <div class="flex justify-between items-start mb-3">
        <div>
            <h3 class="font-semibold">{{ $booking->user->name }}</h3>
            <p class="text-sm text-gray-600">{{ $booking->experience->title }}</p>
        </div>
        <x-status-badge :status="$booking->status" />
    </div>
    
    <div class="space-y-2 text-sm text-gray-600 mb-4">
        <p>📅 {{ $booking->availabilitySlot->start_time->format('d/m/Y H:i') }}</p>
        <p>👥 {{ $booking->num_travelers }} viajero(s)</p>
        <p>💰 ${{ number_format($booking->total_amount, 0) }} COP</p>
    </div>
    
    <div class="flex gap-2">
        @if($booking->status === 'pending')
            <button class="btn-confirm">Confirmar</button>
            <button class="btn-cancel-outline">Rechazar</button>
        @elseif($booking->status === 'confirmed')
            <button class="btn-start">Iniciar Experiencia</button>
        @elseif($booking->status === 'in_progress' && !$booking->guide_confirmed_completed)
            <button class="btn-complete">Marcar Finalizada</button>
        @endif
    </div>
</div>
```

---

## 🎯 Checklist de Implementación

### Frontend
- [ ] Mostrar badge de estado con colores correctos
- [ ] Botones condicionales según estado y rol
- [ ] Confirmación antes de cancelar
- [ ] Deshabilitar botones durante peticiones (loading)
- [ ] Mostrar mensajes de error/éxito
- [ ] Actualizar UI sin recargar (opcional: usar Livewire/Alpine.js)

### Backend
- [ ] Validar permisos en cada acción
- [ ] Implementar transacciones para crear reserva
- [ ] Usar `lockForUpdate` para evitar overbooking
- [ ] Validar transiciones de estado
- [ ] Registrar logs de cambios
- [ ] Enviar notificaciones
- [ ] Procesar reembolsos en cancelaciones
- [ ] Tests automatizados

### Base de Datos
- [ ] Índices en `bookings.user_id`, `bookings.experience_id`, `bookings.status`
- [ ] Constraint para `status` (enum o check)
- [ ] Validar que `num_travelers > 0`
- [ ] Soft deletes (opcional)

---

## ⚡ Preguntas Frecuentes

**¿Qué pasa si el guía no confirma?**
- El sistema puede enviar recordatorios automáticos
- Después de X horas, puede auto-cancelar y reembolsar

**¿Puede cancelarse después de confirmada?**
- Sí, pero pueden aplicar penalizaciones según políticas

**¿Qué pasa si solo uno confirma finalización?**
- El estado permanece `in_progress` hasta que ambos confirmen
- Se puede configurar auto-completado tras Y horas

**¿Se puede editar una reserva?**
- No directamente. Debe cancelar y crear nueva
- Puede implementarse cambio de fecha/viajeros en v2

**¿Cómo se manejan reembolsos?**
- Depende del estado y políticas
- `pending` → 100% reembolso
- `confirmed` → Según días de anticipación
- `in_progress` o `completed` → Sin reembolso

---

## 🚀 Próximas Mejoras

1. **Sistema de recordatorios automáticos**
2. **Auto-completado tras X horas en `in_progress`**
3. **Políticas de cancelación flexibles**
4. **Reprogramación de reservas**
5. **Sistema de cupones/descuentos**
6. **Reservas grupales con split payment**
7. **Chat integrado turista-guía**

---

**Versión:** 1.0  
**Última actualización:** Noviembre 2025

