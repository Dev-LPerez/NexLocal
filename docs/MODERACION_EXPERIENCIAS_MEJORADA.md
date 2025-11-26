# 🔒 Sistema de Moderación de Experiencias - COMPLETADO

## ✅ Problema Resuelto

**Antes:** Las experiencias ocultas/rechazadas por el admin seguían apareciendo en la vista pública.

**Ahora:** El sistema filtra correctamente las experiencias según su estado.

---

## 🎯 Cambios Implementados

### 1. **Filtrado en Vista Pública** (`ExperienceController@index`)

**Antes:**
```php
$query = Experience::with('user')
    ->withCount('reviews')
    ->withAvg('reviews', 'rating')
    ->latest();
```

**Ahora:**
```php
$query = Experience::with('user')
    ->withCount('reviews')
    ->withAvg('reviews', 'rating')
    ->publiclyVisible(); // Solo publicadas, destacadas primero
```

---

### 2. **Protección en Vista Detalle** (`ExperienceController@show`)

Se agregó validación para que experiencias NO publicadas:
- ❌ No sean accesibles por usuarios públicos
- ✅ Solo sean visibles para el dueño (guía)
- ✅ Solo sean visibles para administradores

**Código agregado:**
```php
if ($experience->status !== 'published') {
    if (!Auth::check() || (Auth::id() !== $experience->user_id && Auth::user()->role !== 'admin')) {
        abort(404, 'Esta experiencia no está disponible.');
    }
}
```

---

### 3. **Scopes en Modelo Experience**

Se agregaron 3 scopes útiles:

```php
// Solo experiencias publicadas
Experience::published()->get();

// Solo experiencias destacadas
Experience::featured()->get();

// Experiencias visibles públicamente (publicadas + destacadas primero)
Experience::publiclyVisible()->get();
```

**Definición:**
```php
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

### 4. **Indicadores Visuales en Dashboard del Guía**

El guía ahora ve **badges de estado** en sus experiencias:

- ⭐ **Destacada** - Experiencia promocionada por admin
- 🔒 **Oculta por Admin** - No visible públicamente
- ❌ **Rechazada** - Admin rechazó la experiencia
- 📝 **Borrador** - No publicada todavía

**Características:**
- Fondo amarillo claro en experiencias no publicadas
- Muestra la nota del admin (si existe)
- Permite al guía saber por qué fue moderada

**Ejemplo visual:**
```
┌────────────────────────────────────────────────┐
│ Paseo por el Río Sinú  ⭐ Destacada           │
│ Montería - $50,000                             │
│ [Editar] [Eliminar]                            │
└────────────────────────────────────────────────┘

┌────────────────────────────────────────────────┐ (Fondo amarillo)
│ Tour Nocturno  🔒 Oculta por Admin             │
│ Montería - $30,000                             │
│ 📋 Nota: Las fotos están borrosas, mejóralas  │
│ [Editar] [Eliminar]                            │
└────────────────────────────────────────────────┘
```

---

## 📊 Estados de Experiencias

### Estados Disponibles:
```php
'published'  → Visible públicamente ✅
'hidden'     → Oculta por admin 🔒
'rejected'   → Rechazada por admin ❌
'draft'      → Borrador del guía 📝
```

### Visibilidad:

| Estado     | Vista Pública | Dueño | Admin |
|------------|---------------|-------|-------|
| published  | ✅            | ✅    | ✅    |
| hidden     | ❌            | ✅    | ✅    |
| rejected   | ❌            | ✅    | ✅    |
| draft      | ❌            | ✅    | ✅    |

---

## 🔄 Flujo Completo de Moderación

### Caso 1: Admin Oculta Experiencia

```
1. Admin ve experiencia con fotos de mala calidad
   ↓
2. Admin cambia estado a "hidden"
   ↓
3. Admin agrega nota: "Las fotos están borrosas"
   ↓
4. Sistema envía notificación al guía
   ↓
5. Experiencia desaparece de vista pública
   ↓
6. Guía ve badge "🔒 Oculta por Admin"
   ↓
7. Guía lee la nota y mejora las fotos
   ↓
8. Guía edita la experiencia
   ↓
9. Admin revisa cambios
   ↓
10. Admin cambia estado a "published"
    ↓
11. Experiencia vuelve a ser visible públicamente ✅
```

### Caso 2: Admin Destaca Experiencia de Calidad

```
1. Admin ve experiencia de excelente calidad
   ↓
2. Admin marca como "destacada" (is_featured = true)
   ↓
3. Experiencia aparece PRIMERO en listados públicos
   ↓
4. Guía ve badge "⭐ Destacada" en su dashboard
   ↓
5. Mayor visibilidad = más reservas potenciales
```

---

## 🧪 Cómo Probar

### Prueba 1: Ocultar Experiencia
```
1. Como Admin: Ve a /admin/experiences
2. Encuentra una experiencia
3. Clic en "Estado" → Selecciona "Hidden"
4. Agrega nota: "Mejorar descripción"
5. Guarda
6. Como Usuario Público: Ve a /experiences
7. La experiencia NO aparece en el listado ✅
8. Intenta acceder directo a /experiences/{id}
9. Debe mostrar 404 ✅
10. Como Guía (dueño): Ve al dashboard
11. Ve badge "🔒 Oculta por Admin" ✅
12. Ve la nota del admin ✅
```

### Prueba 2: Destacar Experiencia
```
1. Como Admin: Ve a /admin/experiences
2. Encuentra experiencia de calidad
3. Clic en "⭐ Destacar"
4. Como Usuario Público: Ve a /experiences
5. La experiencia aparece PRIMERO en el listado ✅
6. Como Guía: Ve badge "⭐ Destacada" ✅
```

### Prueba 3: Prioridad de Destacadas
```
1. Crea 3 experiencias
2. Destaca solo 1
3. Ve a /experiences
4. La destacada debe aparecer PRIMERO
5. Incluso si es más antigua ✅
```

---

## 📁 Archivos Modificados

```
✅ app/Http/Controllers/ExperienceController.php
   - index(): Filtro por estado 'published'
   - show(): Validación de acceso

✅ app/Models/Experience.php
   - scopePublished()
   - scopeFeatured()
   - scopePubliclyVisible()

✅ resources/views/dashboard/guide.blade.php
   - Badges de estado
   - Fondo amarillo para no publicadas
   - Muestra notas de moderación
```

---

## 🎯 Beneficios del Sistema

### Para Usuarios/Turistas:
- ✅ Solo ven experiencias de calidad aprobadas
- ✅ Las mejores experiencias aparecen primero (destacadas)
- ✅ No pueden acceder a experiencias rechazadas/ocultas

### Para Guías:
- ✅ Saben exactamente por qué se ocultó su experiencia
- ✅ Ven badges claros del estado
- ✅ Pueden mejorar basándose en feedback del admin
- ✅ Son notificados cuando se les modera

### Para Administradores:
- ✅ Control total sobre qué se muestra públicamente
- ✅ Pueden ocultar temporalmente sin eliminar
- ✅ Pueden destacar experiencias de calidad
- ✅ Dejan notas claras para los guías

---

## 🚀 Mejoras Futuras Opcionales

- [ ] **Auto-publicar tras mejoras**: Si guía edita experiencia oculta, volver a "pending" para revisión
- [ ] **Sistema de apelaciones**: Guía puede solicitar revisión
- [ ] **Estadísticas**: Mostrar % de experiencias moderadas
- [ ] **Filtro en home**: Mostrar solo destacadas en página principal
- [ ] **SEO**: Meta tags especiales para destacadas

---

## ✅ Estado Final

**SISTEMA DE MODERACIÓN 100% FUNCIONAL**

```
✅ Experiencias ocultas NO aparecen en público
✅ Experiencias destacadas aparecen PRIMERO
✅ Guías ven estado y notas de moderación
✅ Scopes reutilizables para consultas
✅ Validación de acceso en vista detalle
✅ Indicadores visuales claros
```

**El sistema de moderación ahora funciona correctamente y las experiencias ocultas están completamente protegidas de la vista pública.**

---

**Fecha:** 2025-11-26  
**Versión:** 1.0.1  
**Estado:** PRODUCCIÓN ✅

