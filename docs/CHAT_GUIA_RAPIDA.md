# 💬 Sistema de Chat - Guía Rápida de Uso

## ¿Qué es?

Un sistema de chat en tiempo real que permite a turistas y guías comunicarse sobre sus reservas activas. Las ventanas de chat se abren desde la parte inferior de la pantalla, estilo Facebook 2018.

## 🚀 Inicio Rápido

### Para Turistas:
1. Realiza una reserva de cualquier experiencia
2. El icono de chat 💬 aparecerá en la barra superior
3. Haz click en el icono para ver tus conversaciones
4. Selecciona una conversación para abrir la ventana de chat
5. ¡Chatea con tu guía sobre la experiencia!

### Para Guías:
1. Recibe una reserva de un turista
2. El icono de chat 💬 aparecerá en la barra superior
3. Haz click en el icono para ver tus conversaciones
4. Selecciona una conversación para abrir la ventana de chat
5. ¡Chatea con tu turista sobre la experiencia!

## ✨ Características Destacadas

### Información de Reserva Visible
Cada ventana de chat muestra:
- 📍 Nombre de la experiencia
- 📅 Fecha de la reserva
- 🕐 Hora de inicio
- 👥 Número de viajeros
- 💰 Precio total
- 🏷️ Estado actual (Pendiente/Confirmada/En Curso/Completada)

### Ventanas Flotantes
- ✅ Se abren desde la parte inferior derecha
- ✅ Puedes abrir hasta 3 ventanas simultáneamente
- ✅ Minimiza haciendo click en el header
- ✅ Cierra con el botón X

### Actualización Automática
- ✅ Los mensajes se actualizan cada 5 segundos
- ✅ No necesitas recargar la página
- ✅ Scroll automático a nuevos mensajes

## 🎨 Cómo Se Ve

```
Barra Superior:
[Logo] [Inicio] [Experiencias]  🌙  💬³  🔔²  [Perfil]
                                    ↑
                              3 mensajes sin leer

Ventana de Chat (inferior derecha):
┌────────────────────────────┐
│ 👤 María López      ⌄  ✕  │ ← Click aquí para minimizar
├────────────────────────────┤
│ 📍 Tour gastronómico      │
│ 📅 2025-11-20  🕐 10:00  │ ← Información de la reserva
│ 👥 2 personas  💰 $80    │   (siempre visible)
│ ✅ Confirmada             │
├────────────────────────────┤
│ Hola, ¿incluye almuerzo?  │ ← Mensaje recibido
│   10:30 AM                │
│                           │
│   ¡Sí! Todo incluido     │ ← Tu mensaje
│              10:31 AM    │
├────────────────────────────┤
│ [Escribe mensaje...] [✈️]│ ← Escribe aquí
└────────────────────────────┘
```

## 🔒 Restricciones

### ¿Quién puede chatear?
Solo usuarios que tienen una **reserva activa** entre ellos:
- Turista ↔️ Guía de su experiencia reservada
- Guía ↔️ Turista que reservó su experiencia

### Estados permitidos:
- ✅ Pendiente
- ✅ Confirmada
- ✅ En Curso
- ✅ Completada
- ❌ Cancelada (NO permite chat)

## 💡 Consejos de Uso

### Para Turistas:
- Pregunta al guía sobre detalles de la experiencia
- Confirma la hora y lugar de encuentro
- Comparte preferencias alimentarias o necesidades especiales
- Solicita recomendaciones sobre qué llevar

### Para Guías:
- Da la bienvenida a tus turistas
- Comparte información útil antes de la experiencia
- Confirma asistencia y detalles logísticos
- Responde dudas rápidamente para generar confianza

## 🎯 Atajos de Teclado

- **Enter**: Enviar mensaje
- **Click en header**: Minimizar/Maximizar ventana
- **X**: Cerrar ventana

## 📱 Responsive

### Desktop:
- Ventanas de 320px de ancho
- Hasta 3 ventanas abiertas
- Posicionadas en la esquina inferior derecha

### Mobile:
- Ventanas adaptadas al ancho de pantalla
- Se recomienda 1 ventana a la vez
- Mismo funcionamiento que desktop

## ❓ Preguntas Frecuentes

### ¿Por qué no veo el icono de chat?
- Necesitas tener al menos una reserva activa
- Debes estar autenticado (iniciado sesión)

### ¿Por qué no puedo chatear con alguien?
- Solo puedes chatear si tienes una reserva con esa persona
- La reserva debe estar en estado activo (no cancelada)

### ¿Los mensajes se borran?
- No, todos los mensajes se guardan en la base de datos
- Puedes ver el historial completo al abrir la conversación

### ¿Puedo chatear con múltiples personas?
- Sí, puedes abrir hasta 3 ventanas de chat simultáneamente

### ¿Cómo sé si tengo mensajes nuevos?
- El icono 💬 muestra un badge verde con el número
- En el dropdown ves cuántos mensajes sin leer por conversación

## 🔧 Solución de Problemas

### Los mensajes no se actualizan
1. Verifica tu conexión a internet
2. Espera 5 segundos (actualización automática)
3. Cierra y vuelve a abrir la ventana

### No puedo enviar mensajes
1. Verifica que el campo no esté vacío
2. Máximo 5000 caracteres por mensaje
3. Verifica que la reserva siga activa

### La ventana no se abre
1. Refresca la página
2. Verifica que tengas una reserva activa
3. Revisa la consola del navegador (F12)

## 📞 Soporte

Para más información técnica, consulta:
- `docs/SISTEMA_CHAT.md` - Documentación completa técnica
- `docs/MANUAL_USUARIO_COMPLETO.md` - Manual general del sistema

---

## 🎊 ¡Disfruta del Chat!

El sistema de chat hace que la comunicación entre turistas y guías sea fácil, rápida y contextual. ¡Aprovéchalo al máximo para mejorar tus experiencias!

