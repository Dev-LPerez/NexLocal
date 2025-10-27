# 🚀 GUÍA DE USO - NexLocal

## 📖 Instrucciones para Usar el Sistema

### 🎯 Inicio Rápido

El sistema **NexLocal** está completamente funcional. Sigue estos pasos para comenzar:

---

## 1️⃣ ACCEDER AL SISTEMA

### Página Principal
```
URL: http://localhost:8000/ (o la URL de tu servidor)
```

En la página principal verás:
- 🔍 Buscador de experiencias
- 📋 Lista de experiencias disponibles
- 🍽️ Sección de restaurantes recomendados

---

## 2️⃣ REGISTRO DE USUARIOS

### Opción A: Registrarse como TURISTA
1. Haz clic en **"Register"** en el menú superior derecho
2. Completa el formulario:
   - Nombre completo
   - Email
   - Contraseña
   - **Selecciona rol:** "Tourist"
3. Haz clic en "Register"
4. Verifica tu email (si está configurado)
5. ¡Listo! Ya puedes reservar experiencias

### Opción B: Registrarse como GUÍA
1. Haz clic en **"Register"** en el menú superior derecho
2. Completa el formulario:
   - Nombre completo
   - Email
   - Contraseña
   - **Selecciona rol:** "Guide"
3. Haz clic en "Register"
4. Verifica tu email (si está configurado)
5. **IMPORTANTE:** Debes verificar tu identidad para publicar experiencias

---

## 3️⃣ FUNCIONALIDADES PARA TURISTAS

### 🔍 Buscar Experiencias
1. Usa el buscador en la página principal
2. Escribe título o ubicación de la experiencia
3. Los resultados se filtrarán automáticamente

### 👀 Ver Detalle de Experiencia
1. Haz clic en cualquier tarjeta de experiencia
2. Verás:
   - Imagen de la experiencia
   - Descripción completa
   - Ubicación y duración
   - Precio
   - Información del guía
   - Formulario de reserva

### 📅 Crear Reserva
1. En la página de detalle de experiencia
2. Selecciona fecha y hora
3. Haz clic en **"Reservar Ahora"**
4. La reserva se creará con estado "Pending"
5. Serás redirigido a "Mis Reservas"

### 📋 Ver Mis Reservas
```
Dashboard → Mis Reservas
O directamente: /bookings
```

Aquí verás:
- Todas tus reservas
- Estado de cada reserva (Pendiente, Confirmada, Cancelada, Completada)
- Botón para cancelar (si está pendiente o confirmada)

### ❌ Cancelar Reserva
1. Ve a "Mis Reservas"
2. Encuentra la reserva que deseas cancelar
3. Haz clic en **"Cancelar Reserva"**
4. Confirma la acción
5. El estado cambiará a "Cancelada"

---

## 4️⃣ FUNCIONALIDADES PARA GUÍAS

### ✅ Verificar Identidad (REQUERIDO)
1. Inicia sesión con tu cuenta de guía
2. Verás una alerta en el Dashboard
3. Haz clic en **"Completa tu verificación aquí"**
4. Sube tu documento de identidad (PDF, JPG, PNG)
5. Espera la aprobación (manual por administrador)

### ➕ Crear Experiencia
```
Dashboard → Experiences → Create
O directamente: /experiences/create
```

Completa el formulario:
- **Título:** Nombre de la experiencia
- **Categoría:** Cultural, Gastronómica, Naturaleza, etc.
- **Descripción:** Detalla qué incluye la experiencia
- **Ubicación:** Ciudad o lugar específico
- **Precio:** Precio por persona en COP
- **Duración:** Ejemplo: "3 horas", "Todo el día"
- **Imagen:** Foto representativa (JPG, PNG, GIF)

### ✏️ Editar Experiencia
1. Ve a Dashboard → Mis Experiencias
2. Haz clic en **"Edit"** en la experiencia que deseas editar
3. Modifica los campos necesarios
4. Haz clic en **"Update Experience"**

### 🗑️ Eliminar Experiencia
1. Ve a Dashboard → Mis Experiencias
2. Haz clic en **"Delete"** en la experiencia que deseas eliminar
3. Confirma la acción
4. La experiencia se eliminará permanentemente

### 📊 Gestionar Reservas
Como guía, puedes:

#### Ver Reservas Recibidas
```
Dashboard → Bookings Received
```

#### Confirmar Reserva
1. Encuentra la reserva pendiente
2. Haz clic en **"Confirm"**
3. El estado cambiará a "Confirmed"
4. El turista será notificado (cuando se implemente)

#### Cancelar Reserva
1. Encuentra la reserva
2. Haz clic en **"Cancel"**
3. Confirma la acción
4. El estado cambiará a "Cancelled"
5. El turista será notificado (cuando se implemente)

---

## 5️⃣ GESTIÓN DE PERFIL

### Editar Información Personal
```
Dashboard → Profile
```

Puedes modificar:
- Nombre
- Email
- Contraseña
- Foto de perfil (próximamente)

### Cambiar Contraseña
1. Ve a Profile
2. Sección "Update Password"
3. Ingresa contraseña actual
4. Ingresa nueva contraseña
5. Confirma nueva contraseña
6. Haz clic en **"Save"**

### Eliminar Cuenta
1. Ve a Profile
2. Sección "Delete Account"
3. Haz clic en **"Delete Account"**
4. Confirma la acción
5. Tu cuenta será eliminada permanentemente

---

## 6️⃣ RUTAS PRINCIPALES

### Públicas (Sin autenticación)
| Ruta | Descripción |
|------|-------------|
| `/` | Página principal |
| `/experiences/{id}` | Detalle de experiencia |
| `/login` | Iniciar sesión |
| `/register` | Registrarse |

### Protegidas (Requiere autenticación)
| Ruta | Descripción | Rol |
|------|-------------|-----|
| `/dashboard` | Dashboard principal | Todos |
| `/profile` | Editar perfil | Todos |
| `/experiences/create` | Crear experiencia | Guía |
| `/experiences/{id}/edit` | Editar experiencia | Guía |
| `/bookings` | Mis reservas | Turista |
| `/verify-identity` | Verificar identidad | Guía |

---

## 7️⃣ ESTADOS DE RESERVA

| Estado | Descripción | Color |
|--------|-------------|-------|
| **Pending** | Reserva creada, esperando confirmación del guía | 🟡 Amarillo |
| **Confirmed** | Guía confirmó la reserva | 🟢 Verde |
| **Cancelled** | Reserva cancelada por turista o guía | 🔴 Rojo |
| **Completed** | Experiencia realizada | 🔵 Azul |

---

## 8️⃣ FLUJO COMPLETO DE USO

### Ejemplo: Turista Reserva una Experiencia

1. **Turista** se registra → Selecciona rol "Tourist"
2. **Turista** busca "Río Sinú" en la homepage
3. **Turista** hace clic en experiencia "Paseo por el Río Sinú"
4. **Turista** selecciona fecha (ej: 30/10/2025, 10:00 AM)
5. **Turista** hace clic en "Reservar Ahora"
6. **Sistema** crea reserva con estado "Pending"
7. **Turista** es redirigido a "Mis Reservas"
8. **Guía** recibe notificación (cuando se implemente)
9. **Guía** va a Dashboard → Bookings Received
10. **Guía** confirma la reserva
11. **Sistema** cambia estado a "Confirmed"
12. **Turista** ve reserva confirmada en "Mis Reservas"
13. *(Día de la experiencia)* **Guía** marca como "Completed"
14. *(Opcional - futuro)* **Turista** deja reseña

---

## 9️⃣ TIPS Y MEJORES PRÁCTICAS

### Para Turistas
- ✅ Lee bien la descripción de la experiencia antes de reservar
- ✅ Verifica la ubicación y duración
- ✅ Cancela con tiempo si no puedes asistir
- ✅ Revisa el perfil del guía

### Para Guías
- ✅ Completa tu verificación de identidad cuanto antes
- ✅ Usa fotos de alta calidad en tus experiencias
- ✅ Describe detalladamente qué incluye la experiencia
- ✅ Responde rápido a las reservas
- ✅ Confirma o cancela reservas a tiempo
- ✅ Mantén actualizada la información de tus experiencias

---

## 🔟 SOLUCIÓN DE PROBLEMAS

### No puedo crear experiencias
- ✅ Verifica que estés registrado como **Guía**
- ✅ Asegúrate de haber **verificado tu identidad**
- ✅ Intenta cerrar sesión y volver a iniciar

### No puedo reservar
- ✅ Verifica que estés registrado como **Turista**
- ✅ Asegúrate de haber iniciado sesión
- ✅ Verifica que la fecha seleccionada sea futura

### No veo mis reservas
- ✅ Ve a: `/bookings` directamente
- ✅ Verifica que hayas creado alguna reserva
- ✅ Asegúrate de estar logueado como Turista

### Las imágenes no se muestran
- ✅ Ejecuta: `php artisan storage:link`
- ✅ Verifica que la carpeta `storage/app/public` exista
- ✅ Verifica permisos de escritura

---

## 📞 SOPORTE

Si encuentras algún problema:

1. Revisa los logs en `storage/logs/laravel.log`
2. Ejecuta `php artisan config:clear`
3. Ejecuta `php artisan cache:clear`
4. Ejecuta `php artisan route:clear`
5. Ejecuta `php artisan view:clear`

---

## 🎉 ¡DISFRUTA NEXLOCAL!

El sistema está completamente funcional y listo para usar. Explora todas las funcionalidades y descubre experiencias únicas en Córdoba.

**Versión:** 2.0  
**Última actualización:** 27 de Octubre, 2025  
**Estado:** ✅ Completamente Operativo

