# 🎨 Guía Visual - Pasarela de Pagos Simulada

## 📸 Vista Previa del Flujo Completo

Esta guía muestra cómo se ve cada pantalla del flujo de pago simulado.

---

## 1️⃣ Página de Experiencia (Inicio del Flujo)

```
┌────────────────────────────────────────────────────────────────┐
│  NexLocal - Detalle de Experiencia                            │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│  🏞️ [IMAGEN DE EXPERIENCIA]                                   │
│                                                                │
│  📍 Tour por el Centro Histórico                              │
│  ⭐ 4.8 (23 reseñas)                                          │
│  💰 $25.00 por persona                                        │
│                                                                │
│  ┌──────────────────────────────────────────┐                │
│  │  📅 Selecciona Fecha                     │                │
│  │  ○ 15 Nov - 10:00 AM (5 cupos)          │                │
│  │  ● 16 Nov - 02:00 PM (8 cupos)          │  ← Seleccionado│
│  │  ○ 17 Nov - 09:00 AM (3 cupos)          │                │
│  │                                          │                │
│  │  👥 Cantidad de viajeros: [2] ▼          │                │
│  │                                          │                │
│  │  ┏━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┓    │                │
│  │  ┃   🛒 Reservar Ahora             ┃    │  ← Click aquí │
│  │  ┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛    │                │
│  └──────────────────────────────────────────┘                │
└────────────────────────────────────────────────────────────────┘
```

**Acción:** Click en "Reservar Ahora" → Redirige a `/checkout`

---

## 2️⃣ Página de Checkout - Formulario de Pago

```
┌────────────────────────────────────────────────────────────────────────┐
│  🔒 Checkout - Pago Seguro                                             │
├────────────────────────────────────────────────────────────────────────┤
│                                                                        │
│  🎓 Modo Demo - Pago Simulado                                         │
│  Esta es una pasarela simulada. Usa: 4532 1234 5678 9010             │
│                                                                        │
│  ┌─────────────────────────────────┬───────────────────────────────┐ │
│  │  💳 FORMULARIO DE PAGO          │  📋 RESUMEN DE RESERVA        │ │
│  │                                 │                               │ │
│  │  🔒 Pago Seguro Garantizado     │  Experiencia:                 │ │
│  │                                 │  Tour por el Centro Histórico │ │
│  │  Número de Tarjeta              │                               │ │
│  │  ┌───────────────────────────┐  │  Fecha y Hora:                │ │
│  │  │ 4532 1234 5678 9010  💳  │  │  16 Nov - 02:00 PM            │ │
│  │  └───────────────────────────┘  │                               │ │
│  │  💳 Aceptamos Visa, MC, Amex    │  Viajeros:                    │ │
│  │                                 │  2 personas                   │ │
│  │  Nombre del Titular             │                               │ │
│  │  ┌───────────────────────────┐  │  ─────────────────────────    │ │
│  │  │ JUAN PEREZ               │  │  Precio por persona: $25.00   │ │
│  │  └───────────────────────────┘  │  Cantidad: × 2                │ │
│  │                                 │  ─────────────────────────    │ │
│  │  Expiración       CVV           │  💰 Total: $50.00             │ │
│  │  ┌──────────┐  ┌──────────┐    │                               │ │
│  │  │ 12/25   │  │   123   │    │  📋 Política de Cancelación:  │ │
│  │  └──────────┘  └──────────┘    │  Gratis hasta 24h antes       │ │
│  │                                 │                               │ │
│  │  ┏━━━━━━━━━━━━━━━━━━━━━━━━┓    │                               │ │
│  │  ┃ 🔒 Pagar $50.00        ┃    │  ← Click para pagar           │ │
│  │  ┗━━━━━━━━━━━━━━━━━━━━━━━━┛    │                               │ │
│  │                                 │                               │ │
│  │  🔒 SSL  ✓ Verificado          │                               │ │
│  └─────────────────────────────────┴───────────────────────────────┘ │
└────────────────────────────────────────────────────────────────────────┘
```

**Acción:** Click en "Pagar" → Abre modal de procesamiento

---

## 3️⃣ Modal de Procesamiento (Aparece sobre Checkout)

```
┌────────────────────────────────────────────────────────────────┐
│  ████████████████████ [Fondo Oscuro Semitransparente]         │
│  ████████████████████                                          │
│  ████████████████████  ┌─────────────────────────────────┐    │
│  ████████████████████  │                                 │    │
│  ████████████████████  │         ⭕ ← Spinner            │    │
│  ████████████████████  │          ⟳  Girando...         │    │
│  ████████████████████  │                                 │    │
│  ████████████████████  │  ⏳ Procesando Pago Seguro...  │    │
│  ████████████████████  │                                 │    │
│  ████████████████████  │  Por favor, no cierres esta    │    │
│  ████████████████████  │  ventana. Estamos verificando  │    │
│  ████████████████████  │  tu transacción.               │    │
│  ████████████████████  │                                 │    │
│  ████████████████████  │  🔒 Conexión encriptada        │    │
│  ████████████████████  │                                 │    │
│  ████████████████████  └─────────────────────────────────┘    │
│  ████████████████████                                          │
└────────────────────────────────────────────────────────────────┘
```

**Duración:** 2 segundos (simulado con `sleep(2)`)
**Acción:** Redirige automáticamente a página de éxito

---

## 4️⃣ Página de Confirmación de Pago Exitoso

```
┌────────────────────────────────────────────────────────────────────────┐
│  ✅ ¡Pago Exitoso!                                                     │
├────────────────────────────────────────────────────────────────────────┤
│                                                                        │
│                        ┌────────┐                                     │
│                        │   ✓   │  ← Animación bounce                 │
│                        └────────┘                                     │
│                                                                        │
│              🎉 ¡Reserva Confirmada!                                  │
│           Tu pago ha sido procesado exitosamente                      │
│                                                                        │
│  ┌──────────────────────────────────────────────────────────────────┐ │
│  │  📋 Detalles de tu Reserva                                       │ │
│  │                                                                  │ │
│  │  Número de Reserva                                               │ │
│  │  ┏━━━━━━━━━━━━━━┓                                                │ │
│  │  ┃  #000042     ┃  ← Destacado en grande                        │ │
│  │  ┗━━━━━━━━━━━━━━┛                                                │ │
│  │                                                                  │ │
│  │  Experiencia              Estado                                │ │
│  │  Tour Centro Histórico    ⏳ Pendiente de Confirmación          │ │
│  │                                                                  │ │
│  │  Fecha y Hora             Viajeros                              │ │
│  │  16 Nov - 02:00 PM        2 personas                            │ │
│  │                                                                  │ │
│  │  ────────────────────────────────────────────────────────────   │ │
│  │  💰 Total Pagado: $50.00                                        │ │
│  └──────────────────────────────────────────────────────────────────┘ │
│                                                                        │
│  ┌──────────────────────────────────────────────────────────────────┐ │
│  │  ✅ Información de Pago                                          │ │
│  │                                                                  │ │
│  │  Estado del Pago          Método de Pago                        │ │
│  │  ✓ Pagado                 Tarjeta Simulada                      │ │
│  │                                                                  │ │
│  │  ID de Transacción        Fecha de Pago                         │ │
│  │  pi_mock_abc123xyz        26/11/2025 14:30                      │ │
│  └──────────────────────────────────────────────────────────────────┘ │
│                                                                        │
│  ┌──────────────────────────────────────────────────────────────────┐ │
│  │  📋 Próximos Pasos                                               │ │
│  │                                                                  │ │
│  │  1. El guía revisará tu solicitud de reserva                    │ │
│  │  2. Recibirás una notificación cuando sea confirmada            │ │
│  │  3. Podrás chatear con el guía desde "Mis Reservas"             │ │
│  │  4. Disfruta tu experiencia en la fecha programada              │ │
│  └──────────────────────────────────────────────────────────────────┘ │
│                                                                        │
│  ┌──────────────────────────┐  ┌──────────────────────────────────┐  │
│  │  📋 Ver Mis Reservas     │  │  🏠 Explorar Más Experiencias   │  │
│  └──────────────────────────┘  └──────────────────────────────────┘  │
│                                                                        │
│  📧 Se ha enviado una copia de tu reserva a tu correo electrónico    │
│                                                                        │
└────────────────────────────────────────────────────────────────────────┘
```

**Acciones disponibles:**
- Click en "Ver Mis Reservas" → `/bookings`
- Click en "Explorar Más" → `/` (home)

---

## 5️⃣ Vista en "Mis Reservas" - Confirmación Final

```
┌────────────────────────────────────────────────────────────────┐
│  📋 Mis Reservas                                               │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│  ┌──────────────────────────────────────────────────────────┐ │
│  │  #000042  Tour por el Centro Histórico                   │ │
│  │                                                          │ │
│  │  📅 16 Nov 2025 - 02:00 PM                              │ │
│  │  👥 2 personas                                          │ │
│  │  💰 $50.00                                              │ │
│  │                                                          │ │
│  │  Estado: ⏳ Pendiente de Confirmación                   │ │
│  │  Pago:   ✅ Pagado                                      │ │
│  │                                                          │ │
│  │  [💬 Chat]  [❌ Cancelar]                               │ │
│  └──────────────────────────────────────────────────────────┘ │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```

---

## 🎨 Elementos Visuales Destacados

### **Colores Principales:**

```
🟣 Indigo (#4F46E5)   → Botones primarios
🟢 Verde (#10B981)    → Éxito, pagado
🟡 Amarillo (#F59E0B) → Pendiente
🔴 Rojo (#EF4444)     → Errores, cancelado
⚫ Gris (#6B7280)     → Textos secundarios
```

### **Iconos Utilizados:**

```
🔒 → Seguridad
💳 → Tarjetas/Pago
✅ → Confirmación
⏳ → Procesando/Pendiente
📋 → Resumen/Detalles
💰 → Precio/Total
👥 → Personas/Viajeros
📅 → Fecha
🏠 → Inicio/Home
💬 → Chat
❌ → Cancelar
🎓 → Demo/Académico
```

### **Animaciones:**

```
⭕ Spinner:        Rotación 360° continua
✓ Check:           Bounce suave (2s loop)
Modal:             Fade in/out (0.3s)
Botones:           Hover scale(1.02)
Formulario:        Focus ring indigo
```

---

## 📱 Vista Responsive (Móvil)

```
┌────────────────────┐
│  🔒 Checkout       │
├────────────────────┤
│                    │
│  🎓 Modo Demo     │
│  Pago Simulado    │
│                    │
│  ┌──────────────┐ │
│  │ FORMULARIO   │ │
│  │              │ │
│  │ 💳 Tarjeta   │ │
│  │ [4532 1234]  │ │
│  │              │ │
│  │ 👤 Titular   │ │
│  │ [JUAN PEREZ] │ │
│  │              │ │
│  │ 📅 12/25     │ │
│  │ 🔒 123       │ │
│  └──────────────┘ │
│                    │
│  ┌──────────────┐ │
│  │ RESUMEN      │ │
│  │              │ │
│  │ Tour Centro  │ │
│  │ 16 Nov 2PM   │ │
│  │ 2 personas   │ │
│  │              │ │
│  │ Total:       │ │
│  │ $50.00       │ │
│  └──────────────┘ │
│                    │
│  ┏━━━━━━━━━━━━━━┓ │
│  ┃ Pagar $50    ┃ │
│  ┗━━━━━━━━━━━━━━┛ │
│                    │
└────────────────────┘
```

**Diseño:** Stack vertical en móvil, 2 columnas en desktop

---

## 🌙 Modo Oscuro

```
┌────────────────────────────────────────────────────────────────┐
│  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░  │
│  ░  🔒 Checkout - Pago Seguro                              ░  │
│  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░  │
│                                                                │
│  Fondo: #1F2937 (Gray 800)                                    │
│  Texto: #F9FAFB (Gray 100)                                    │
│  Inputs: #374151 (Gray 700)                                   │
│  Bordes: #4B5563 (Gray 600)                                   │
│                                                                │
│  Totalmente funcional en Dark Mode ✓                          │
│                                                                │
└────────────────────────────────────────────────────────────────┘
```

---

## 🎯 Estados del Formulario

### **Estado Normal:**
```
┌──────────────────────────┐
│ 4532 1234 5678 9010     │  ← Borde gris
└──────────────────────────┘
```

### **Estado Focus:**
```
┌══════════════════════════┐
│ 4532 1234 5678 9010 ▊   │  ← Borde indigo brillante
└══════════════════════════┘
```

### **Estado Error:**
```
┌──────────────────────────┐
│ 123                      │  ← Borde rojo
└──────────────────────────┘
⚠️ Mínimo 15 dígitos requeridos
```

### **Estado Válido:**
```
┌──────────────────────────┐
│ 4532 1234 5678 9010  ✓  │  ← Check verde
└──────────────────────────┘
```

---

## 🔄 Transiciones de Estado

```
Reservar → Checkout → Procesando → Éxito → Mis Reservas
   ↓          ↓            ↓          ↓         ↓
 [Form]    [Card]      [Modal]    [Check]   [List]
  0.3s      0.2s        2.0s       0.5s      -
```

---

## 📊 Indicadores de Progreso

```
Paso 1: Seleccionar Experiencia  ✅
Paso 2: Checkout                 ✅
Paso 3: Procesar Pago            ✅ ← Actual
Paso 4: Confirmación             ⏳
```

---

## 🎬 Secuencia de Animación Completa

```
T=0s    : Usuario click "Pagar"
T=0.1s  : Botón se deshabilita
T=0.2s  : Modal aparece (fade in)
T=0.3s  : Spinner inicia rotación
T=0.5s  : Texto "Procesando..." aparece
T=2.0s  : Backend retorna success
T=2.1s  : Modal desaparece (fade out)
T=2.2s  : Redirigiendo...
T=2.5s  : Página de éxito cargada
T=2.6s  : Check ✓ aparece
T=2.7s  : Bounce animation inicia
```

---

**🎨 Implementación visual completada y lista para demo!**

_Ver archivos:_
- `resources/views/bookings/checkout.blade.php`
- `resources/views/bookings/success.blade.php`

