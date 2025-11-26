# 💳 Pasarela de Pagos Simulada - Quick Start

## 🚀 Inicio Rápido (5 minutos)

### 1️⃣ Probar el Flujo Completo

**Paso 1:** Inicia sesión como turista
```
Cualquier cuenta con role = 'tourist'
```

**Paso 2:** Ve a cualquier experiencia
```
http://localhost:8000/experiences/1
```

**Paso 3:** Selecciona fecha y cantidad de viajeros, haz clic en "Reservar Ahora"

**Paso 4:** Llena el formulario de checkout con estos datos de prueba:

```
Tarjeta:     4532 1234 5678 9010
Titular:     JUAN PEREZ
Expiración:  12/25
CVV:         123
```

**Paso 5:** Haz clic en "Pagar" y observa:
- ⏳ Modal de procesamiento con spinner
- ⏱️ Delay de 2 segundos (simulación realista)
- ✅ Redirección a página de éxito

**Paso 6:** Verifica la reserva creada en:
```
http://localhost:8000/bookings
```

---

## 🎯 Datos de Tarjeta para Pruebas

Puedes usar **cualquier número de 15-19 dígitos**. Ejemplos:

```
✅ 4532 1234 5678 9010  (Visa)
✅ 5425 2334 3010 9903  (Mastercard)
✅ 3782 822463 10005    (Amex)
✅ 1234 5678 9012 3456  (Genérico)
```

**Nombre:** Cualquier texto
**Expiración:** MM/AA (mes futuro)
**CVV:** 3 o 4 dígitos

---

## 📁 Archivos Modificados/Creados

### **Controllers:**
- `app/Http/Controllers/BookingController.php` - Añadidos métodos: `showCheckout()`, `processPayment()`, `checkoutSuccess()`

### **Views:**
- `resources/views/bookings/checkout.blade.php` - Página de pago (NUEVO)
- `resources/views/bookings/success.blade.php` - Confirmación (NUEVO)

### **Routes:**
- `routes/web.php` - Rutas de checkout añadidas

### **Docs:**
- `docs/PASARELA_PAGOS_SIMULADA.md` - Documentación completa

---

## 🎨 Características Visuales

### **Página de Checkout:**
- ✨ Formulario de tarjeta con auto-formateo
- 🔒 Indicadores de seguridad SSL
- 📱 Responsive (mobile-friendly)
- 🌙 Modo oscuro completo
- 💰 Resumen de reserva lateral

### **Modal de Procesamiento:**
- 🔄 Spinner animado
- ⏳ Mensaje "Procesando Pago Seguro..."
- 🔐 Icono de seguridad

### **Página de Éxito:**
- ✅ Animación de confirmación
- 📋 Detalles completos de reserva
- 💳 Información de transacción
- 🎯 Próximos pasos
- 🔘 Botones de acción

---

## 🔍 Verificación del Flujo

### **Base de Datos:**

Después de un pago exitoso, verifica en la tabla `bookings`:

```sql
SELECT id, payment_intent_id, payment_status, payment_method, total_amount 
FROM bookings 
ORDER BY created_at DESC 
LIMIT 1;
```

Deberías ver:
```
payment_intent_id: pi_mock_xxxxxxxxxx
payment_status: succeeded
payment_method: tarjeta_simulada
```

---

## 🛠️ Personalización Rápida

### **Cambiar el delay de procesamiento:**

En `BookingController.php`, línea ~100:

```php
// Cambiar de 2 a N segundos
sleep(2); // ← Aquí
```

### **Modificar estilos del checkout:**

Edita `resources/views/bookings/checkout.blade.php`

Busca las clases de Tailwind y personaliza colores/tamaños.

---

## 🐛 Solución de Problemas

| Problema | Solución |
|----------|----------|
| "No hay datos de reserva" | Sesión expiró, vuelve a reservar |
| Modal no cierra | Revisa consola del navegador (F12) |
| No redirige después de pagar | Verifica ruta `checkout.success` existe |
| Error 500 al pagar | Revisa logs: `storage/logs/laravel.log` |
| Error "Call to member function format() on null" | Ya corregido: Se cambió `$slot->date` por `$slot->start_time` |

---

## ⚠️ Nota Importante sobre AvailabilitySlot

El modelo `AvailabilitySlot` usa los campos:
- `start_time` (datetime) - Fecha y hora de inicio
- `end_time` (datetime) - Fecha y hora de fin

**NO** tiene un campo `date`. Asegúrate de usar `$slot->start_time` en el código.

---

## 📞 Comandos Útiles

```bash
# Ver rutas de checkout
php artisan route:list --name=checkout

# Limpiar caché de vistas
php artisan view:clear

# Limpiar sesiones
php artisan session:flush

# Ver logs en tiempo real
tail -f storage/logs/laravel.log
```

---

## ✅ Checklist de Funcionalidad

Verifica que todo funcione:

- [ ] Botón "Reservar Ahora" redirige a `/checkout`
- [ ] Formulario de tarjeta se muestra correctamente
- [ ] Auto-formateo de número de tarjeta funciona
- [ ] Al hacer clic en "Pagar" aparece modal
- [ ] Después de 2 segundos redirige a `/success`
- [ ] Reserva se crea en base de datos
- [ ] Notificación se envía al guía
- [ ] Página de éxito muestra todos los datos

---

## 🎓 Para Presentación/Demo

**Script de demostración (1 minuto):**

1. "Como turista, exploro experiencias disponibles"
2. "Encuentro una que me gusta y hago clic en Reservar"
3. "El sistema me lleva a un checkout seguro y profesional"
4. "Ingreso los datos de mi tarjeta (simulada para la demo)"
5. "El sistema procesa el pago con feedback visual"
6. "Recibo confirmación inmediata con todos los detalles"
7. "El guía es notificado automáticamente"

**Puntos técnicos a mencionar:**
- Simulación realista sin APIs externas
- Validación doble (frontend + backend)
- Experiencia de usuario profesional
- Fácil de reemplazar por Stripe en producción

---

## 🚀 Próximos Pasos (Opcional)

Si quieres expandir la funcionalidad:

1. **Añadir más métodos de pago:**
   - PayPal simulado
   - Transferencia bancaria
   - Efectivo (pago en persona)

2. **Mejorar validaciones:**
   - Algoritmo de Luhn para número de tarjeta
   - Validar fecha de expiración real
   - CVV según tipo de tarjeta

3. **Añadir recibos:**
   - Generar PDF con DomPDF
   - Enviar por email con Mailtrap
   - Descargar desde "Mis Reservas"

---

**¡Listo para usar! 🎉**

Cualquier duda, revisa `docs/PASARELA_PAGOS_SIMULADA.md` para documentación completa.

