# Sistema de Chat - NexLocal

## Descripción General

El sistema de chat de NexLocal permite la comunicación en tiempo real entre turistas y guías que tienen una reserva activa. El chat está diseñado con ventanas flotantes estilo Facebook (2018), que se abren desde la parte inferior de la pantalla.

## Características Principales

### 1. **Icono de Chat en la Navegación**
- Ubicado en la barra superior, entre el botón de dark mode y las notificaciones
- Muestra un contador en verde con el número de mensajes no leídos
- Al hacer clic, despliega un dropdown con todas las conversaciones activas

### 2. **Dropdown de Conversaciones**
- Muestra todas las conversaciones basadas en reservas activas
- Cada conversación incluye:
  - Foto de perfil del otro usuario (o inicial en círculo de color)
  - Nombre del usuario
  - Título de la experiencia
  - Último mensaje enviado
  - Contador de mensajes no leídos (badge verde)
- Se actualiza automáticamente cada 15 segundos

### 3. **Ventanas de Chat Flotantes (Estilo Facebook 2018)**
- Se abren desde la parte inferior derecha de la pantalla
- Máximo 3 ventanas abiertas simultáneamente
- Cada ventana incluye:
  - **Header con información del usuario**:
    - Foto de perfil
    - Nombre del usuario
    - Título de la experiencia
    - Botones para minimizar y cerrar
  
  - **Panel de información de la reserva** (siempre visible):
    - 📍 Título de la experiencia
    - 📅 Fecha de la reserva
    - 🕐 Hora de la reserva
    - 👥 Número de viajeros
    - 💰 Monto total
    - Estado de la reserva (con colores):
      - Pendiente (amarillo)
      - Confirmada (verde)
      - En Curso (azul)
      - Completada (morado)
  
  - **Área de mensajes**:
    - Scroll automático a los nuevos mensajes
    - Mensajes propios alineados a la derecha (azul)
    - Mensajes recibidos alineados a la izquierda (blanco/gris)
    - Timestamp de cada mensaje
    - Se actualiza automáticamente cada 5 segundos
  
  - **Input de mensaje**:
    - Campo de texto con diseño redondeado
    - Botón de envío con icono de avión
    - Límite de 5000 caracteres

### 4. **Funcionalidades**
- **Minimizar/Maximizar**: Click en el header o en el botón de flecha
- **Cerrar**: Botón X en el header
- **Múltiples ventanas**: Hasta 3 ventanas abiertas al mismo tiempo
- **Actualización automática**: Los mensajes se cargan cada 5 segundos
- **Marcar como leído**: Los mensajes se marcan automáticamente al abrir la conversación

## Restricciones de Acceso

### ¿Quién puede chatear?
Solo pueden chatear usuarios que tienen una reserva activa entre ellos:
- **Turistas**: Pueden chatear con los guías de sus reservas
- **Guías**: Pueden chatear con los turistas que reservaron sus experiencias

### Estados de reserva permitidos:
- ✅ Pendiente (pending)
- ✅ Confirmada (confirmed)
- ✅ En Curso (in_progress)
- ✅ Completada (completed)
- ❌ Cancelada (NO permite chat)

## Arquitectura del Sistema

### Modelos y Relaciones

```php
// ChatMessage
- id
- booking_id (FK a bookings)
- sender_id (FK a users)
- receiver_id (FK a users)
- message
- is_read
- read_at
- created_at, updated_at

// Relaciones:
- belongsTo(Booking)
- belongsTo(User, 'sender_id')
- belongsTo(User, 'receiver_id')
```

### Controlador: ChatController

#### Métodos disponibles:

1. **getConversations()**
   - Obtiene todas las conversaciones del usuario autenticado
   - Retorna: Lista de conversaciones con información del otro usuario, experiencia y último mensaje

2. **getMessages($bookingId)**
   - Obtiene todos los mensajes de una conversación
   - Marca los mensajes como leídos automáticamente
   - Retorna: Mensajes, información del otro usuario e información de la reserva

3. **sendMessage(Request $request, $bookingId)**
   - Envía un nuevo mensaje en una conversación
   - Valida: mensaje requerido, máximo 5000 caracteres
   - Retorna: El mensaje creado

4. **getUnreadCount()**
   - Obtiene el contador total de mensajes no leídos del usuario
   - Retorna: Número de mensajes sin leer

## Rutas API

```php
GET  /chat/conversations          → Obtener todas las conversaciones
GET  /chat/{bookingId}/messages   → Obtener mensajes de una conversación
POST /chat/{bookingId}/send       → Enviar un mensaje
GET  /chat/unread-count           → Obtener contador de mensajes no leídos
```

## Componentes Frontend

### 1. **chatDropdown()** (Alpine.js)
Ubicado en: `resources/views/layouts/navigation.blade.php`

Funciones:
- `init()`: Carga conversaciones iniciales y configura polling cada 15s
- `toggleDropdown()`: Abre/cierra el dropdown
- `loadConversations()`: Carga la lista de conversaciones vía API
- `openChatWindow(conversation)`: Abre una ventana de chat

### 2. **chatWindows()** (Alpine.js)
Ubicado en: `resources/views/components/chat-windows.blade.php`

Funciones:
- `openWindow(conversation)`: Abre una nueva ventana de chat
- `closeWindow(bookingId)`: Cierra una ventana
- `toggleMinimize(bookingId)`: Minimiza/maximiza una ventana
- `sendMessage(bookingId)`: Envía un mensaje
- `loadMessages(bookingId)`: Actualiza los mensajes de una ventana
- `startPolling(bookingId)`: Inicia actualización automática cada 5s
- `scrollToBottom(bookingId)`: Hace scroll al último mensaje
- `formatMessageTime(timestamp)`: Formatea la hora del mensaje
- `translateStatus(status)`: Traduce el estado de la reserva

## Flujo de Uso

### Para Turistas:
1. Realiza una reserva de una experiencia
2. El icono de chat aparece en la barra de navegación
3. Click en el icono de chat → Se muestra el dropdown con conversaciones
4. Click en una conversación → Se abre la ventana de chat flotante
5. Puede ver la información de la reserva y chatear con el guía

### Para Guías:
1. Recibe una reserva de un turista
2. El icono de chat aparece en la barra de navegación
3. Click en el icono de chat → Se muestra el dropdown con conversaciones
4. Click en una conversación → Se abre la ventana de chat flotante
5. Puede ver la información de la reserva y chatear con el turista

## Diseño Responsive

### Desktop (> 640px):
- Ventanas flotantes desde la parte inferior derecha
- Hasta 3 ventanas simultáneas
- Ancho: 320px (w-80)
- Alto: 384px (h-96) cuando está maximizada, 48px (h-12) cuando está minimizada

### Mobile (< 640px):
- El dropdown de chat funciona igual
- Las ventanas de chat se adaptan al ancho de la pantalla
- Se recomienda abrir solo 1 ventana a la vez en móviles

## Estilos y Colores

### Estados de Reserva:
- **Pendiente**: `bg-yellow-100 text-yellow-800` (amarillo)
- **Confirmada**: `bg-green-100 text-green-800` (verde)
- **En Curso**: `bg-blue-100 text-blue-800` (azul)
- **Completada**: `bg-purple-100 text-purple-800` (morado)

### Mensajes:
- **Propios**: `bg-indigo-600 text-white` (azul oscuro)
- **Recibidos**: `bg-white text-gray-900` o `bg-gray-700 text-gray-100` (dark mode)

### Badges:
- **Mensajes no leídos**: `bg-green-600` (verde)
- **Contador en icono**: Badge verde con borde redondeado

## Actualizaciones Automáticas

### Polling Intervals:
- **Dropdown de conversaciones**: Cada 15 segundos
- **Mensajes en ventanas abiertas**: Cada 5 segundos
- **Contador de no leídos**: Se actualiza con las conversaciones

> **Nota**: El sistema usa polling (consultas periódicas) en lugar de WebSockets para simplificar la implementación. Para un sistema de chat en tiempo real más eficiente, se recomienda implementar WebSockets en el futuro.

## Seguridad

### Validaciones:
1. **Autenticación**: Solo usuarios autenticados pueden acceder al chat
2. **Autorización**: Solo pueden chatear usuarios con reservas entre ellos
3. **CSRF Protection**: Todas las peticiones POST incluyen token CSRF
4. **XSS Protection**: Los mensajes se escapan automáticamente con `x-text`
5. **Límite de caracteres**: 5000 caracteres por mensaje

### Verificaciones en cada acción:
```php
// Verificar que el usuario tenga acceso a la conversación
$isAuthorized = ($user->role === 'tourist' && $booking->user_id === $user->id) ||
               ($user->role === 'guide' && $booking->experience->user_id === $user->id);

if (!$isAuthorized) {
    abort(403, 'No tienes acceso a esta conversación.');
}
```

## Base de Datos

### Tabla: chat_messages

```sql
CREATE TABLE chat_messages (
    id BIGINT PRIMARY KEY,
    booking_id BIGINT NOT NULL,
    sender_id BIGINT NOT NULL,
    receiver_id BIGINT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_booking_created (booking_id, created_at),
    INDEX idx_sender_receiver (sender_id, receiver_id)
);
```

## Integración con el Sistema

El chat se integra automáticamente con:
- ✅ Sistema de reservas (Bookings)
- ✅ Sistema de usuarios (User roles: tourist/guide)
- ✅ Sistema de experiencias (Experiences)
- ✅ Dark mode
- ✅ Diseño responsive

## Ejemplo de Uso Programático

### Obtener conversaciones de un usuario:
```javascript
const response = await fetch('/chat/conversations');
const data = await response.json();
console.log(data.conversations);
```

### Enviar un mensaje:
```javascript
const response = await fetch(`/chat/${bookingId}/send`, {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({ message: 'Hola!' })
});
```

## Futuras Mejoras Sugeridas

1. **WebSockets**: Implementar Laravel Reverb o Pusher para mensajes en tiempo real
2. **Notificaciones**: Integrar con el sistema de notificaciones cuando llega un mensaje nuevo
3. **Adjuntos**: Permitir enviar imágenes o archivos
4. **Emojis**: Añadir selector de emojis
5. **Indicador de escritura**: Mostrar "Usuario está escribiendo..."
6. **Historial completo**: Página dedicada para ver todo el historial de mensajes
7. **Búsqueda**: Buscar en mensajes antiguos
8. **Videollamadas**: Integrar sistema de videollamadas para consultas previas
9. **Plantillas**: Mensajes predefinidos para guías (FAQ)
10. **Sonido**: Notificación sonora al recibir mensajes

## Troubleshooting

### El chat no aparece
- ✅ Verificar que el usuario esté autenticado
- ✅ Verificar que tenga al menos una reserva activa
- ✅ Revisar la consola del navegador para errores

### Los mensajes no se actualizan
- ✅ Verificar la conexión a internet
- ✅ Revisar que el polling esté funcionando (cada 5s)
- ✅ Verificar permisos de la reserva

### No puedo enviar mensajes
- ✅ Verificar que el campo no esté vacío
- ✅ Verificar que no exceda 5000 caracteres
- ✅ Verificar que la reserva siga activa

## Archivos del Sistema

### Backend:
- `app/Models/ChatMessage.php`
- `app/Http/Controllers/ChatController.php`
- `database/migrations/2025_11_16_204852_create_chat_messages_table.php`
- `database/seeders/ChatMessageSeeder.php`

### Frontend:
- `resources/views/components/chat-windows.blade.php`
- `resources/views/layouts/navigation.blade.php` (icono y dropdown)
- `resources/views/layouts/app.blade.php` (inclusión del componente)

### Rutas:
- `routes/web.php` (rutas del chat)

---

## 🎯 Estado: ¡COMPLETAMENTE FUNCIONAL!

El sistema de chat está listo para usar. Solo necesitas tener una reserva activa con otro usuario para comenzar a chatear.

