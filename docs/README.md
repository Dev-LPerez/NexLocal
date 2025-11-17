# Documentación del Sistema de Reservas - NexLocal

Bienvenido a la documentación del sistema de gestión de reservas de NexLocal.

---

## 📚 Documentos Disponibles

### 1. [Manual de Usuario Completo](./MANUAL_USUARIO_COMPLETO.md) ⭐ **NUEVO - CON CAPTURAS**
**Audiencia:** Turistas y Guías (usuarios finales)  
**Contenido:**
- **64 indicaciones de capturas de pantalla** (qué tomar y qué señalar)
- Recorrido completo desde visitante sin cuenta
- Cómo crear cuenta (turista o guía)
- Navegación completa como turista
- Navegación completa como guía
- Sistema de reservas explicado visualmente
- Reseñas y calificaciones
- Versión móvil
- Consejos y mejores prácticas

**Cuándo usarlo:**
- ✅ Necesitas crear un tutorial visual con capturas
- ✅ Quieres documentación completa con imágenes
- ✅ Vas a capacitar usuarios nuevos
- ✅ Necesitas un manual ilustrado paso a paso

---

### 2. [Manual de Usuario](./MANUAL_USUARIO.md)
**Audiencia:** Turistas y Guías (usuarios finales)  
**Contenido:**
- Lenguaje simple y no técnico
- Guía paso a paso para turistas
- Guía paso a paso para guías
- Ejemplos prácticos con nombres reales
- Preguntas frecuentes
- Consejos útiles
- Soporte y contacto

**Cuándo usarlo:**
- ✅ Necesitas referencia rápida sin imágenes
- ✅ Quieres imprimir un manual de texto
- ✅ Prefieres lectura sin ilustraciones

---

### 3. [Manual Completo de Estados de Reservas](./MANUAL_ESTADOS_RESERVAS.md)
**Audiencia:** Turistas, Guías, Product Managers, QA  
**Contenido:**
- Estados de reserva y sus significados
- Flujo completo de una reserva
- Vista detallada del turista
- Vista detallada del guía
- Reglas y permisos
- Gestión de cupos
- Ejemplos prácticos
- Casos especiales
- Recomendaciones técnicas

**Cuándo usarlo:**
- ✅ Necesitas entender cómo funciona el sistema de reservas
- ✅ Vas a capacitar a nuevos usuarios
- ✅ Necesitas resolver dudas sobre estados y transiciones
- ✅ Estás diseñando nuevas funcionalidades

---

### 4. [Guía Rápida de Estados](./GUIA_RAPIDA_ESTADOS.md)
**Audiencia:** Desarrolladores, Diseñadores UX/UI  
**Contenido:**
- Diagrama visual de estados
- Tabla rápida de acciones
- Badges de estado (UI)
- Navegación rápida
- Ejemplos de interfaz
- Checklist de implementación
- Preguntas frecuentes

**Cuándo usarlo:**
- ✅ Necesitas una referencia rápida
- ✅ Estás diseñando la interfaz de usuario
- ✅ Necesitas implementar badges o indicadores visuales
- ✅ Quieres validar que la UI cumple con los requisitos

---

### 5. [Ejemplos de Código](./EJEMPLOS_CODIGO_RESERVAS.md)
**Audiencia:** Desarrolladores Backend/Frontend  
**Contenido:**
- Crear una reserva con transacciones seguras
- Validar disponibilidad
- Implementar máquina de estados
- Crear notificaciones
- Tests automatizados
- Componentes Blade
- JavaScript/Alpine.js
- Optimización de queries

**Cuándo usarlo:**
- ✅ Estás implementando nuevas funcionalidades
- ✅ Necesitas ejemplos de código copy-paste
- ✅ Vas a escribir tests
- ✅ Quieres mejorar el rendimiento

---

## 🎯 Inicio Rápido

### Para Crear Tutorial Visual (con capturas de pantalla)
1. Lee el [Manual de Usuario Completo](./MANUAL_USUARIO_COMPLETO.md) ⭐
2. Sigue las **64 indicaciones de capturas** incluidas
3. Toma las fotos de cada pantalla según se indica
4. Señala los elementos mencionados en cada captura

### Para Nuevos Usuarios (Turistas o Guías)
1. Lee el [Manual de Usuario](./MANUAL_USUARIO.md)
2. Sigue los pasos para tu rol (Turista o Guía)
3. Consulta las [preguntas frecuentes](./MANUAL_USUARIO.md#-preguntas-frecuentes)

### Para Turistas
1. Lee la sección [Guía para Turistas](./MANUAL_USUARIO.md#-guía-para-turistas)
2. Revisa cómo [hacer una reserva](./MANUAL_USUARIO.md#-2-cómo-hacer-una-reserva)
3. Aprende sobre los [estados de tu reserva](./MANUAL_USUARIO.md#-3-ver-mis-reservas)

### Para Guías
1. Lee la sección [Guía para Guías](./MANUAL_USUARIO.md#-guía-para-guías)
2. Aprende a [crear tu primera experiencia](./MANUAL_USUARIO.md#-1-crear-tu-primera-experiencia)
3. Descubre cómo [gestionar reservas](./MANUAL_USUARIO.md#-2-recibir-y-confirmar-reservas)

### Para Desarrolladores (Técnico)
1. Lee [Reglas y Permisos](./MANUAL_ESTADOS_RESERVAS.md#reglas-y-permisos)
2. Revisa [Ejemplos de Código](./EJEMPLOS_CODIGO_RESERVAS.md)
3. Implementa los [tests automatizados](./EJEMPLOS_CODIGO_RESERVAS.md#tests-automatizados)

### Para Product Managers
1. Lee el [Manual Completo](./MANUAL_ESTADOS_RESERVAS.md)
2. Revisa [Casos Especiales](./MANUAL_ESTADOS_RESERVAS.md#casos-especiales)
3. Consulta [Próximas Mejoras](./GUIA_RAPIDA_ESTADOS.md#próximas-mejoras)

---

## 🔄 Estados del Sistema

| Estado | Emoji | Descripción Breve |
|--------|-------|-------------------|
| `pending` | 🟡 | Reserva creada, esperando confirmación del guía |
| `confirmed` | 🟢 | Guía confirmó, experiencia programada |
| `in_progress` | 🔵 | Experiencia en curso |
| `completed` | ✅ | Experiencia finalizada (ambos confirmaron) |
| `cancelled` | 🔴 | Reserva cancelada |

---

## 📋 Checklist de Implementación

Usa esta lista para validar que todo está implementado correctamente:

### Backend
- [ ] Crear reserva con `DB::transaction` y `lockForUpdate`
- [ ] Validar disponibilidad de cupos
- [ ] Validar que la fecha no haya pasado
- [ ] Prevenir reservas duplicadas
- [ ] Implementar máquina de estados
- [ ] Validar permisos en cada transición
- [ ] Devolver cupos al cancelar
- [ ] Sistema de dos pasos para completar
- [ ] Logs de auditoría
- [ ] Tests automatizados (mín. 80% coverage)

### Notificaciones
- [ ] Nueva reserva → Guía
- [ ] Reserva confirmada → Turista
- [ ] Reserva cancelada → Ambos
- [ ] Experiencia completada → Ambos
- [ ] Recordatorio de reseña → Turista

### Frontend - Turista
- [ ] Formulario de reserva con validación
- [ ] Lista de mis reservas
- [ ] Filtros por estado
- [ ] Badge de estado con colores correctos
- [ ] Botón "Cancelar" (condicional)
- [ ] Botón "Marcar completada" (condicional)
- [ ] Botón "Escribir reseña" (condicional)
- [ ] Modal de confirmación para cancelar
- [ ] Loading states en botones
- [ ] Mensajes de error/éxito

### Frontend - Guía
- [ ] Dashboard con reservas recibidas
- [ ] Filtros por estado
- [ ] Botón "Confirmar" (solo pending)
- [ ] Botón "Iniciar" (solo confirmed)
- [ ] Botón "Marcar finalizada" (solo in_progress)
- [ ] Botón "Cancelar/Rechazar"
- [ ] Información del turista visible
- [ ] Notificaciones en tiempo real (opcional)

### Base de Datos
- [ ] Índices en `bookings.user_id`
- [ ] Índices en `bookings.experience_id`
- [ ] Índices en `bookings.status`
- [ ] Constraint para enum `status`
- [ ] Campos `tourist_confirmed_completed` y `guide_confirmed_completed`
- [ ] Soft deletes (opcional)

---

## 🚨 Problemas Comunes y Soluciones

### Overbooking (Dos reservas para el mismo cupo)
**Problema:** Dos usuarios reservan simultáneamente y se exceden los cupos.  
**Solución:** Usar `DB::transaction` con `lockForUpdate` ([Ver ejemplo](./EJEMPLOS_CODIGO_RESERVAS.md#crear-una-reserva))

### No se devuelven los cupos al cancelar
**Problema:** `availabilitySlot` es null o `num_travelers` no está guardado.  
**Solución:** Validar que ambos existen antes de incrementar ([Ver código](./MANUAL_ESTADOS_RESERVAS.md#gestión-de-cupos))

### Solo uno confirma finalización y la reserva no se completa
**Problema:** Falta implementar el sistema de dos pasos.  
**Solución:** Usar campos `tourist_confirmed_completed` y `guide_confirmed_completed` ([Ver lógica](./EJEMPLOS_CODIGO_RESERVAS.md#cambiar-estado-de-reserva))

### Error 403 al cambiar estado
**Problema:** Usuario sin permisos intenta una transición.  
**Solución:** Validar `$isGuide` o `$isTourist` según la acción ([Ver permisos](./MANUAL_ESTADOS_RESERVAS.md#reglas-y-permisos))

### Query N+1 en lista de reservas
**Problema:** Muchas queries al listar reservas.  
**Solución:** Usar eager loading `->with()` ([Ver optimización](./EJEMPLOS_CODIGO_RESERVAS.md#query-optimization))

---

## 📞 Soporte y Contribuciones

- **Preguntas:** Crea un issue en el repositorio
- **Bugs:** Reporta en GitHub Issues con etiqueta `bug`
- **Mejoras:** Pull requests son bienvenidos
- **Documentación:** Si encuentras errores o mejoras, actualiza estos docs

---

## 📈 Roadmap

### v1.1 (Próximo release)
- [ ] Auto-confirmación tras X horas de inactividad
- [ ] Políticas de cancelación flexibles por experiencia
- [ ] Sistema de cupones/descuentos

### v1.2
- [ ] Reprogramación de reservas
- [ ] Chat integrado turista-guía
- [ ] Notificaciones push

### v2.0
- [ ] Reservas grupales con split payment
- [ ] Sistema de membresías
- [ ] API pública

---

## 🔗 Enlaces Útiles

- [Repositorio GitHub](https://github.com/nexlocal)
- [Documentación Técnica](../README.md)
- [Guía de Contribución](../CONTRIBUTING.md)
- [Changelog](../CHANGELOG.md)

---

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Ver [LICENSE](../LICENSE) para más detalles.

---

**Última actualización:** Noviembre 2025  
**Versión de la documentación:** 1.0  
**Mantenido por:** Equipo de Desarrollo NexLocal

---

## 🎓 Para Nuevos Desarrolladores

### Día 1: Familiarización
- [ ] Leer [Manual Completo](./MANUAL_ESTADOS_RESERVAS.md)
- [ ] Revisar [Guía Rápida](./GUIA_RAPIDA_ESTADOS.md)
- [ ] Configurar entorno local

### Día 2-3: Práctica
- [ ] Revisar [Ejemplos de Código](./EJEMPLOS_CODIGO_RESERVAS.md)
- [ ] Ejecutar tests existentes
- [ ] Crear una reserva manualmente en local

### Semana 1: Implementación
- [ ] Implementar una pequeña mejora
- [ ] Escribir tests para tu código
- [ ] Crear PR con la mejora

### Recursos de Aprendizaje
- Laravel Docs: https://laravel.com/docs
- Testing en Laravel: https://laravel.com/docs/testing
- Eloquent Relationships: https://laravel.com/docs/eloquent-relationships

---

¡Gracias por usar NexLocal! 🚀

