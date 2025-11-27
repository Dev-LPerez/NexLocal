1. ✅ Usuario autenticado
2. ✅ Usuario tiene campo `is_suspended = true`
3. ✅ Ruta actual no está en la lista de permitidas
4. ✅ No es ruta de contraseña

### Verificaciones en la Vista:
1. ✅ Usuario autenticado antes de mostrar banner
2. ✅ Usuario realmente suspendido antes de mostrar página
3. ✅ Redirige a dashboard si no está suspendido

### Protección de Experiencias:
1. ✅ No aparecen en búsquedas públicas
2. ✅ No se pueden crear nuevas
3. ✅ No se pueden editar existentes

---

## 🧪 Casos de Prueba

### Caso 1: Guía Suspendido Intenta Crear Experiencia
```
✅ Esperado: Redirigido a /account/suspended
✅ Banner visible en todas las páginas
✅ No puede acceder a formulario de creación
```

### Caso 2: Usuario Suspendido Intenta Reservar
```
✅ Esperado: Redirigido a /account/suspended
✅ Mensaje informativo mostrado
✅ Botón de contacto a soporte visible
```

### Caso 3: Búsqueda de Experiencias
```
✅ Experiencias de suspendidos NO aparecen
✅ Solo se muestran experiencias de usuarios activos
```

### Caso 4: Usuario No Suspendido
```
✅ No ve banner de suspensión
✅ Acceso normal a todas las funciones
✅ No puede acceder a /account/suspended (redirige a dashboard)
```

---

## 📧 Email Pre-rellenado a Soporte

**Asunto:** Solicitud de reactivación de cuenta

**Cuerpo:**
```
Hola,

Mi correo es: [email del usuario]

Deseo solicitar información sobre la suspensión de mi cuenta.

[Usuario completa el resto]
```

---

## 🎯 Beneficios de la Implementación

### Para el Usuario:
- ✅ Información clara sobre su estado
- ✅ Sabe exactamente qué puede y qué no puede hacer
- ✅ Tiene un camino claro para resolver el problema
- ✅ Contacto directo con soporte

### Para la Plataforma:
- ✅ Control total sobre usuarios problemáticos
- ✅ Contenido de suspendidos oculto automáticamente
- ✅ No se pierde al usuario (puede recuperar cuenta)
- ✅ Protección contra acciones no autorizadas

### Para el Administrador:
- ✅ Suspensión efectiva inmediata
- ✅ Razón visible para el usuario
- ✅ Usuario puede contactar directamente
- ✅ Sistema robusto y difícil de eludir

---

## 🔄 Flujo de Reactivación

1. **Usuario recibe suspensión** → Ve banner y página
2. **Lee motivo** → Entiende la razón
3. **Contacta soporte** → Usa botón de email
4. **Soporte revisa** → Analiza el caso
5. **Admin restaura** → `$user->restore()`
6. **Usuario recupera acceso** → Banner desaparece, puede usar todas las funciones

---

## 🛠️ Mantenimiento

### Para cambiar email de soporte:
Editar `resources/views/account/suspended.blade.php`:
```blade
<a href="mailto:nuevo-soporte@nexlocal.com">
```

### Para cambiar horario:
Editar la vista en la sección de contacto:
```blade
<p>Horario: Lunes a Domingo, 24/7</p>
```

### Para agregar más rutas permitidas:
Editar `app/Http/Middleware/CheckIfSuspended.php`:
```php
$allowedRoutes = [
    'account.suspended',
    'logout',
    'profile.edit',
    'nueva.ruta.permitida', // ✅ Agregar aquí
];
```

---

## ✅ Checklist de Implementación

- [x] Middleware `CheckIfSuspended` creado
- [x] Middleware registrado en `bootstrap/app.php`
- [x] Controlador `AccountSuspendedController` creado
- [x] Vista `account/suspended.blade.php` creada
- [x] Ruta `/account/suspended` agregada
- [x] Banner agregado en `layouts/app.blade.php`
- [x] Middleware aplicado a rutas críticas
- [x] Experiencias de suspendidos ocultas
- [x] Métodos `suspend()`, `restore()`, `isSuspended()` en modelo User
- [x] Migraciones para campos de suspensión
- [x] Documentación completa

---

## 🚀 Comandos Útiles

```bash
# Suspender un usuario (desde tinker o código)
$user = User::find(1);
$user->suspend('Violación de términos de servicio');

# Restaurar un usuario
$user->restore();

# Verificar si está suspendido
$user->isSuspended(); // true o false

# Limpiar caché de rutas
php artisan route:clear
```

---

## 📝 Notas Importantes

1. **El middleware NO hace logout** - El usuario sigue logueado pero con restricciones
2. **Las experiencias existentes no se eliminan** - Solo se ocultan
3. **El usuario puede ver su perfil** - Para actualizar información de contacto
4. **Los datos de suspensión se guardan** - Razón, fecha, etc.
5. **El sistema es reversible** - Admin puede restaurar fácilmente

---

## 🎓 Conclusión

Este sistema proporciona un control robusto y transparente sobre usuarios suspendidos, bloqueando efectivamente sus acciones críticas mientras mantiene la comunicación abierta y ofrece un camino claro para la resolución del problema.
# Sistema de Suspensión de Usuarios - Documentación Completa

## 🎯 Objetivo

Implementar un sistema robusto de suspensión de usuarios que:
- Bloquee todas las acciones críticas de usuarios suspendidos
- Muestre información clara sobre la suspensión
- Oculte contenido de usuarios suspendidos
- Proporcione información de contacto a soporte

---

## 🔧 Componentes Implementados

### 1. **Middleware: CheckIfSuspended**
**Ubicación:** `app/Http/Middleware/CheckIfSuspended.php`

**Función:** Intercepta todas las peticiones de usuarios autenticados y verifica si están suspendidos.

**Comportamiento:**
- ✅ Permite acceso a: `account.suspended`, `logout`, `profile.edit`, `profile.show`
- ✅ Permite rutas de cambio de contraseña
- ❌ Bloquea todas las demás rutas
- 🔀 Redirige a la página de suspensión con mensaje informativo

**Código clave:**
```php
if (Auth::check() && Auth::user()->isSuspended()) {
    $allowedRoutes = ['account.suspended', 'logout', 'profile.edit', 'profile.show'];
    
    if (!in_array($currentRoute, $allowedRoutes) && 
        !str_starts_with($currentRoute, 'password.')) {
        return redirect()->route('account.suspended')
            ->with('warning', 'Tu cuenta está suspendida.');
    }
}
```

---

### 2. **Controlador: AccountSuspendedController**
**Ubicación:** `app/Http/Controllers/AccountSuspendedController.php`

**Función:** Gestiona la página de cuenta suspendida.

**Método principal:**
```php
public function index(): View
{
    if (!auth()->check() || !auth()->user()->isSuspended()) {
        return redirect()->route('dashboard');
    }

    return view('account.suspended', [
        'user' => auth()->user(),
        'reason' => auth()->user()->suspension_reason,
        'suspended_at' => auth()->user()->suspended_at,
    ]);
}
```

---

### 3. **Vista: suspended.blade.php**
**Ubicación:** `resources/views/account/suspended.blade.php`

**Características:**
- 🚨 Alerta prominente de suspensión con fecha
- 📝 Muestra el motivo de la suspensión
- ℹ️ Lista de restricciones y permisos
- 📧 Información de contacto a soporte
- 🔗 Botones de acción (contactar soporte, ver perfil, logout)

**Elementos visuales:**
1. **Banner rojo** con icono de advertencia
2. **Sección de información** sobre qué significa estar suspendido
3. **Instrucciones** de cómo resolver la suspensión
4. **Datos de contacto** de soporte con email pre-rellenado
5. **Advertencia final** con información importante

---

### 4. **Banner de Alerta en Layout**
**Ubicación:** `resources/views/layouts/app.blade.php`

**Función:** Muestra un banner rojo en la parte superior de todas las páginas.

**Código:**
```blade
@auth
    @if(auth()->user()->isSuspended())
        <div class="bg-red-600 text-white">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="flex-1 flex items-center">
                        <span class="flex p-2 rounded-lg bg-red-800">
                            <!-- Icono de advertencia -->
                        </span>
                        <p class="ml-3 font-medium">
                            ⚠️ Tu cuenta ha sido suspendida...
                        </p>
                    </div>
                    <a href="{{ route('account.suspended') }}" class="...">
                        Ver detalles
                    </a>
                </div>
            </div>
        </div>
    @endif
@endauth
```

---

### 5. **Protección de Rutas**
**Ubicación:** `routes/web.php`

**Rutas protegidas con middleware `check.suspended`:**

```php
// Experiencias (solo guías verificados y no suspendidos)
Route::resource('experiences', ExperienceController::class)
    ->except(['index', 'show'])
    ->middleware(['verified.guide', 'check.suspended']);

// Reservas y checkout
Route::middleware('check.suspended')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/checkout', [BookingController::class, 'showCheckout']);
    Route::post('/checkout/process', [BookingController::class, 'processPayment']);
    Route::get('/reviews/create', [ReviewController::class, 'create']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    // ... más rutas
});
```

---

### 6. **Ocultamiento de Experiencias**
**Ubicación:** `app/Http/Controllers/ExperienceController.php`

**Modificación:** Las experiencias de usuarios suspendidos no aparecen en búsquedas.

```php
$query = Experience::with('user')
    ->withCount('reviews')
    ->withAvg('reviews', 'rating')
    ->publiclyVisible()
    ->whereHas('user', function ($q) {
        $q->where('is_suspended', false);  // ✅ Excluir suspendidos
    });
```

---

## 🎨 Flujo de Usuario Suspendido

### Escenario: Guía intenta crear una experiencia

1. **Usuario accede a `/experiences/create`**
2. **Middleware intercepta** → Verifica `isSuspended()` = true
3. **Redirige a** `/account/suspended` con mensaje
4. **Muestra página** con:
   - Alerta roja de suspensión
   - Motivo de la suspensión
   - Lista de restricciones
   - Información de contacto
   - Botones de acción

### Escenario: Usuario suspendido navega por la web

1. **En cada página** ve el banner rojo superior
2. **Al intentar acción bloqueada** (crear experiencia, reservar):
   - Middleware lo redirige a `/account/suspended`
3. **Puede acceder a**:
   - ✅ Ver su perfil
   - ✅ Ver página de suspensión
   - ✅ Cerrar sesión
   - ✅ Cambiar contraseña
4. **NO puede acceder a**:
   - ❌ Crear/editar experiencias
   - ❌ Hacer reservas
   - ❌ Dejar reseñas
   - ❌ Dashboard completo

---

## 📊 Información Mostrada al Usuario

### En el Banner (Layout):
```
⚠️ Tu cuenta ha sido suspendida. No puedes crear experiencias ni hacer reservas.
Razón: [Primeros 50 caracteres del motivo]
[Botón: Ver detalles]
```

### En la Página de Suspensión:

**1. Alerta Principal**
- Título: "Tu cuenta ha sido suspendida"
- Fecha de suspensión
- Motivo completo (si existe)

**2. ¿Qué significa esto?**
- ❌ No puedes crear nuevas experiencias
- ❌ No puedes realizar nuevas reservas
- ❌ Tus experiencias están ocultas
- ✅ Puedes ver tu perfil
- ✅ Puedes contactar a soporte

**3. ¿Cómo resolverlo?**
- Revisar el motivo
- Contactar soporte
- Proporcionar información
- Esperar revisión

**4. Datos de Contacto**
- Email: soporte@nexlocal.com
- Horario: Lunes a Viernes, 9 AM - 6 PM
- Usuario afectado: [email del usuario]

**5. Acciones Disponibles**
- Botón: "Contactar Soporte" (abre email pre-rellenado)
- Botón: "Ver Mi Perfil"
- Botón: "Cerrar Sesión"

---

## 🔒 Seguridad y Validaciones

### Verificaciones del Middleware:

