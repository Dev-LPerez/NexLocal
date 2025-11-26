# 📖 Guía de Uso - Sistema de Verificación de Identidad

## 🎯 Para Guías Turísticos

### Paso 1: Acceder a la Verificación

Después de registrarte como guía, verás una alerta en tu dashboard:

1. Inicia sesión en tu cuenta
2. Ve a tu **Panel de Guía** (Dashboard)
3. Verás una alerta amarilla que dice: **"Verificación de Identidad Requerida"**
4. Haz clic en el botón **"Verificar Mi Identidad Ahora"**

### Paso 2: Preparar tus Documentos

Antes de comenzar, asegúrate de tener:

✅ **Documento de identidad válido** (cédula, pasaporte, DNI, etc.)  
✅ **Foto del FRENTE** del documento  
✅ **Foto del REVERSO** del documento  
✅ Las fotos deben ser **claras y legibles**  
✅ Formatos aceptados: **JPG, PNG, PDF**  
✅ Tamaño máximo: **5MB por archivo**  

### Paso 3: Subir los Documentos

1. En la página de verificación, verás dos áreas de carga
2. **Primera área**: Sube la foto del FRENTE de tu documento
   - Puedes arrastrar el archivo o hacer clic para seleccionarlo
   - Verás el nombre del archivo seleccionado
3. **Segunda área**: Sube la foto del REVERSO de tu documento
   - Mismo proceso que el frontal
4. Haz clic en **"📤 Enviar Documentos"**

### Paso 4: Esperar la Revisión

Después de enviar:

- ✅ Verás un mensaje de confirmación
- ⏳ Tu estado cambia a **"Pendiente de Revisión"**
- 📧 Los administradores recibirán una notificación
- ⌚ La revisión toma **24-48 horas hábiles** generalmente
- 🔔 Recibirás una notificación cuando sea revisado

### Paso 5A: Si tu Verificación es Aprobada ✅

Recibirás una notificación de aprobación:

- ✅ Tu estado cambia a **"Verificado"**
- 🎉 El mensaje de tu dashboard cambia a verde
- 🚀 El botón **"Crear Nueva Experiencia"** se habilita
- 🎯 Puedes crear, editar y gestionar experiencias sin restricciones

### Paso 5B: Si tu Verificación es Rechazada ❌

Recibirás una notificación con la razón del rechazo:

- ❌ Verás la razón específica en tu dashboard
- 📝 Ejemplo: "El documento está borroso, por favor envía una imagen más nítida"
- 🔄 Puedes **enviar nuevos documentos** inmediatamente
- 💡 Asegúrate de corregir el problema mencionado

---

## 👨‍💼 Para Administradores

### Acceder al Panel de Verificación

1. Inicia sesión con tu cuenta de administrador
2. En la navegación superior, haz clic en **"Panel Admin"** (en rojo)
3. En el dashboard, verás:
   - Estadísticas generales del sistema
   - **"Verificaciones Pendientes"** (número de solicitudes)
4. Haz clic en **"Verificación"** en el menú o el contador

### Revisar una Solicitud

En la página de verificación verás:

```
┌─────────────────────────────────────────┐
│ 👤 Juan Pérez                           │
│ 📧 juan@example.com                     │
│ 📅 Registrado: 25/11/2025               │
│ ⏳ Estado: Pendiente                    │
│                                         │
│ 📄 Documento Frontal  [Ver/Descargar]   │
│ 📄 Documento Trasero  [Ver/Descargar]   │
│                                         │
│ [Rechazar ❌]  [Aprobar Verificación ✅] │
└─────────────────────────────────────────┘
```

### Ver los Documentos

1. Haz clic en **"Ver/Descargar"** en documento frontal
2. Se abrirá en una nueva pestaña o descargará el archivo
3. Haz lo mismo con el documento trasero
4. Verifica que:
   - ✅ Las fotos sean claras y legibles
   - ✅ Correspondan a la misma persona
   - ✅ Los datos coincidan con el nombre registrado
   - ✅ El documento esté vigente (si es visible la fecha)

### Aprobar una Solicitud ✅

Si los documentos son válidos:

1. Haz clic en **"Aprobar Verificación"**
2. Confirma en el cuadro de diálogo
3. El sistema:
   - ✅ Marca al guía como verificado
   - 🔔 Envía notificación al guía
   - ✅ Le permite crear experiencias
4. La solicitud desaparece de la lista de pendientes

### Rechazar una Solicitud ❌

Si los documentos tienen problemas:

1. Haz clic en **"Rechazar"**
2. Se abre un modal pidiendo la **razón del rechazo**
3. Escribe una razón clara y específica:
   - ✅ **Bueno**: "El documento está borroso y no se puede leer la información claramente. Por favor, envía una imagen más nítida."
   - ❌ **Malo**: "Rechazado" (sin explicación)
4. Haz clic en **"Confirmar Rechazo"**
5. El sistema:
   - ❌ Marca como rechazado
   - 🔔 Envía notificación al guía con la razón
   - 📤 Permite que el guía envíe nuevos documentos

### Buenas Prácticas para Administradores

**Revisa diariamente:**
- 📧 Revisa las notificaciones de nuevas solicitudes
- ⚡ Intenta revisar dentro de 24 horas
- 📊 Mantén el contador de pendientes en 0

**Al rechazar, sé específico:**
- ✅ "La foto del reverso está cortada, falta ver la firma"
- ✅ "El documento parece ser de otra persona, no coincide con el nombre registrado"
- ✅ "La calidad de la imagen es muy baja, por favor usa mejor iluminación"
- ❌ Evita: "No sirve", "Mal", "Rechazado" (sin contexto)

**Seguridad:**
- 🔒 Nunca compartas documentos de identidad
- 🔒 Solo descarga cuando sea necesario revisar
- 🔒 Los archivos están protegidos en almacenamiento privado

---

## 🔄 Flujo Completo de Ejemplo

### Ejemplo: María se registra como Guía

**Día 1 - 10:00 AM:**
1. María se registra en la plataforma con rol "Guía"
2. Accede a su dashboard y ve la alerta de verificación
3. Toma fotos claras de su cédula (frente y reverso)
4. Las sube en el formulario de verificación
5. Recibe confirmación: "Documentos enviados con éxito"

**Día 1 - 10:01 AM:**
6. El admin Carlos recibe notificación: "Nueva solicitud de verificación de María"

**Día 1 - 2:00 PM:**
7. Carlos revisa los documentos de María
8. Todo está correcto y claro
9. Carlos hace clic en "Aprobar Verificación"
10. María recibe notificación instantánea: "¡Verificación Aprobada!"

**Día 1 - 3:00 PM:**
11. María ve su dashboard actualizado con mensaje verde
12. El botón "Crear Nueva Experiencia" está habilitado
13. María crea su primera experiencia turística ✅

### Ejemplo: Juan tiene problemas con su documento

**Día 1:**
1. Juan sube documentos pero la foto está borrosa
2. Admin Pedro rechaza con razón: "Foto borrosa, reenvía con mejor calidad"
3. Juan recibe notificación del rechazo

**Día 2:**
4. Juan toma nuevas fotos con mejor cámara
5. Las envía nuevamente
6. Admin Pedro aprueba
7. Juan puede crear experiencias ✅

---

## ❓ Preguntas Frecuentes

### Para Guías:

**P: ¿Cuánto tarda la verificación?**  
R: Generalmente 24-48 horas hábiles. Depende de la disponibilidad del administrador.

**P: ¿Qué hago si mi documento fue rechazado?**  
R: Lee la razón del rechazo, corrige el problema y envía nuevos documentos. Puedes hacerlo inmediatamente.

**P: ¿Puedo usar mi pasaporte en vez de cédula?**  
R: Sí, cualquier documento de identidad oficial válido es aceptado.

**P: ¿Por qué necesito verificarme?**  
R: Para garantizar la seguridad y confianza de todos los usuarios de la plataforma.

**P: ¿Puedo ver experiencias mientras espero?**  
R: Sí, puedes explorar la plataforma normalmente. Solo no puedes crear experiencias hasta ser verificado.

### Para Administradores:

**P: ¿Cómo sé si los documentos son auténticos?**  
R: Verifica que sean claros, legibles, y que los datos coincidan con el nombre del usuario.

**P: ¿Qué hago si tengo dudas sobre un documento?**  
R: En caso de duda, es mejor rechazar con una explicación clara y pedir documentos más claros.

**P: ¿Cuántas solicitudes puedo tener pendientes?**  
R: No hay límite, pero se recomienda mantener el contador en 0 revisando diariamente.

**P: ¿Puedo deshacer una aprobación?**  
R: Por el momento no está implementado. Revisa cuidadosamente antes de aprobar.

---

## 🔐 Seguridad y Privacidad

### Protección de Datos:

- 🔒 Los documentos se almacenan en carpeta privada
- 🔒 No son accesibles vía URL directa
- 🔒 Solo administradores autenticados pueden verlos
- 🔒 Se recomienda borrar documentos antiguos periódicamente

### Cumplimiento:

- ✅ Proceso transparente con el usuario
- ✅ Razones claras de rechazo
- ✅ Notificaciones en cada paso
- ✅ El usuario controla cuándo enviar documentos

---

## 🎉 ¡Listo para Usar!

El sistema está completamente funcional y listo para producción. 

**Guías:** Verifica tu identidad para comenzar a crear experiencias increíbles.  
**Admins:** Revisa las solicitudes diariamente para mantener la plataforma segura.

¡Bienvenidos a la comunidad de turismo verificada! 🌍✨

