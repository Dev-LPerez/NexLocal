# Sistema de Notificaciones - NexLocal

## Descripción General

El sistema de notificaciones de NexLocal permite a los usuarios recibir alertas en tiempo real sobre eventos importantes relacionados con sus reservas, experiencias y actividades en la plataforma.

## Características Principales

### 1. **Icono de Campana en la Navegación**
- Ubicado en la barra superior, al lado del botón de dark mode y del perfil del usuario
- Muestra un contador en rojo con el número de notificaciones no leídas
- Al hacer clic, despliega un dropdown con las últimas 5 notificaciones

### 2. **Dropdown de Notificaciones**
- Muestra las 5 notificaciones más recientes no leídas
- Cada notificación incluye:
  - Icono personalizado (emoji)
  - Título
  - Mensaje descriptivo
  - Tiempo transcurrido (ej: "Hace 5 min", "Hace 2 h")
  - Indicador visual de no leída (punto azul)
- Botón para marcar todas como leídas
- Enlace para ver todas las notificaciones

### 3. **Página de Notificaciones**
- Vista completa de todas las notificaciones del usuario
- Paginación de 15 notificaciones por página
- Acciones disponibles:
  - Marcar como leída individualmente
  - Ver detalles (si tiene enlace asociado)
  - Eliminar notificación
- Estado visual diferenciado entre leídas y no leídas

### 4. **Actualización Automática**
- El contador y la lista de notificaciones se actualizan automáticamente cada 30 segundos
- No requiere recargar la página

## Tipos de Notificaciones

### Para Turistas:
1. **Reserva Confirmada** (✅)
   - Cuando el guía confirma una reserva pendiente
   
2. **Reserva Cancelada** (❌)
   - Cuando el guía cancela una reserva confirmada
   
3. **Experiencia Completada** (🎉)
   - Cuando ambas partes marcan la experiencia como completada
   - Incluye enlace para dejar una reseña

4. **Recordatorio de Experiencia** (🔔)
   - Recordatorio antes de una experiencia próxima

### Para Guías:
1. **Nueva Reserva** (📅)
   - Cuando un turista realiza una nueva reserva
   
2. **Reserva Cancelada** (❌)
   - Cuando un turista cancela una reserva
   
3. **Nueva Reseña** (⭐)
   - Cuando un turista deja una reseña
   - Muestra la calificación recibida
   
4. **Pago Recibido** (💰)
   - Confirmación de pago procesado

## Uso del NotificationHelper

Para crear notificaciones desde cualquier controlador:

```php
use App\Helpers\NotificationHelper;

// Notificar reserva confirmada
NotificationHelper::bookingConfirmed($user, $booking);

// Notificar nueva reserva al guía
NotificationHelper::newBookingForGuide($guide, $booking);

// Notificar reserva cancelada
NotificationHelper::bookingCancelled($user, $booking, 'guide');

// Notificar experiencia completada
NotificationHelper::bookingCompleted($user, $booking);

// Notificar nueva reseña
NotificationHelper::newReview($guide, $review);

// Notificación personalizada
NotificationHelper::custom(
    $user,
    'custom_type',
    'Título de la notificación',
    'Mensaje descriptivo',
    '🎁', // Icono opcional
    route('some.route') // Enlace opcional
);
```

## Rutas Disponibles

- `GET /notifications` - Ver todas las notificaciones
- `GET /notifications/unread` - API para obtener notificaciones no leídas (JSON)
- `PATCH /notifications/{id}/read` - Marcar una notificación como leída
- `POST /notifications/mark-all-read` - Marcar todas como leídas
- `DELETE /notifications/{id}` - Eliminar una notificación

## Estructura de Base de Datos

La tabla `notifications` contiene:
- `id` - ID único
- `user_id` - Usuario que recibe la notificación
- `type` - Tipo de notificación
- `title` - Título corto
- `message` - Mensaje descriptivo
- `icon` - Emoji o icono
- `link` - URL opcional a donde dirigir al usuario
- `is_read` - Booleano que indica si fue leída
- `read_at` - Timestamp de cuándo fue leída
- `created_at` y `updated_at`

## Integración con el Sistema

Las notificaciones se generan automáticamente en los siguientes eventos:

1. **BookingController**:
   - Al crear una reserva → notifica al guía
   - Al confirmar una reserva → notifica al turista
   - Al cancelar una reserva → notifica a la otra parte
   - Al completar una experiencia → notifica al turista

2. **ReviewController**:
   - Al crear una reseña → notifica al guía

## Personalización

### Cambiar el intervalo de actualización

En `navigation.blade.php`, línea 270:
```javascript
// Actualizar notificaciones cada 30 segundos (30000ms)
setInterval(() => {
    this.loadNotifications();
}, 30000);
```

### Cambiar el número de notificaciones en el dropdown

En `NotificationController.php`, método `unread()`:
```php
->take(5) // Cambiar este número
```

### Cambiar notificaciones por página

En `NotificationController.php`, método `index()`:
```php
->paginate(15) // Cambiar este número
```

## Diseño Responsive

- En pantallas grandes: Dropdown con notificaciones al hacer clic en la campana
- En pantallas móviles: Enlace en el menú hamburguesa con contador de no leídas

## Compatibilidad

- Funciona con Alpine.js (ya incluido en el proyecto)
- Compatible con modo oscuro (dark mode)
- Responsive para todos los tamaños de pantalla
- Sin necesidad de WebSockets (usa polling cada 30s)

## Futuras Mejoras Sugeridas

1. Implementar WebSockets para notificaciones en tiempo real
2. Agregar notificaciones por email
3. Agregar notificaciones push en navegadores
4. Permitir al usuario configurar qué tipos de notificaciones quiere recibir
5. Agregar sonido al recibir notificaciones nuevas
6. Implementar categorías de notificaciones

