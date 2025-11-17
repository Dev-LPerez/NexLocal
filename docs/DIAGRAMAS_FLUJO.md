# Diagramas de Flujo - Sistema de Reservas

Este documento contiene diagramas visuales del flujo de reservas usando Mermaid.

> **Nota:** Estos diagramas se renderizan automáticamente en GitHub, GitLab, y muchos editores de Markdown.

---

## 📊 Diagrama Principal de Estados

```mermaid
stateDiagram-v2
    [*] --> pending: Turista reserva
    
    pending --> confirmed: Guía confirma
    pending --> cancelled: Turista/Guía cancela
    
    confirmed --> in_progress: Guía inicia
    confirmed --> cancelled: Turista/Guía cancela
    
    in_progress --> completed: Ambos confirman finalización
    in_progress --> cancelled: Turista/Guía cancela
    
    completed --> [*]
    cancelled --> [*]
    
    note right of pending
        Estado inicial
        Pago procesado
        Cupos decrementados
    end note
    
    note right of confirmed
        Guía aceptó
        Esperando fecha
    end note
    
    note right of in_progress
        Experiencia en curso
        Requiere confirmación
        de ambas partes
    end note
    
    note right of completed
        Finalizada
        Reseña habilitada
    end note
    
    note right of cancelled
        Cupos devueltos
        Reembolso procesado
    end note
```

---

## 🔄 Flujo de Creación de Reserva

```mermaid
sequenceDiagram
    actor T as Turista
    participant UI as Interfaz
    participant BE as Backend
    participant DB as Base de Datos
    participant G as Guía
    
    T->>UI: Selecciona experiencia y fecha
    UI->>T: Muestra formulario de reserva
    
    T->>UI: Completa formulario (num_travelers)
    UI->>BE: POST /bookings
    
    BE->>BE: Validar datos
    BE->>DB: Iniciar transacción
    BE->>DB: lockForUpdate(slot)
    
    alt Cupos suficientes
        BE->>DB: Verificar available_spots
        BE->>BE: Procesar pago
        BE->>DB: Crear booking (status: pending)
        BE->>DB: Decrementar available_spots
        BE->>DB: Commit transacción
        BE->>G: 📧 Notificación nueva reserva
        BE->>UI: Éxito + Redirect
        UI->>T: "¡Reserva exitosa!"
    else Sin cupos
        BE->>DB: Rollback
        BE->>UI: Error
        UI->>T: "No hay cupos suficientes"
    end
```

---

## ✅ Flujo de Confirmación por el Guía

```mermaid
sequenceDiagram
    actor G as Guía
    participant UI as Dashboard
    participant BE as Backend
    participant DB as Base de Datos
    participant T as Turista
    
    G->>UI: Accede a dashboard
    UI->>BE: GET /dashboard
    BE->>DB: Obtener reservas pending
    DB->>BE: Lista de reservas
    BE->>UI: Mostrar reservas
    
    G->>UI: Click en "Confirmar"
    UI->>BE: PATCH /bookings/{id}/status (status: confirmed)
    
    BE->>BE: Verificar que G es dueño de experience
    BE->>BE: Validar estado actual = pending
    
    BE->>DB: UPDATE booking SET status = 'confirmed'
    DB->>BE: OK
    
    BE->>T: 📧 Notificación: Reserva confirmada
    BE->>UI: Éxito
    UI->>G: "Reserva confirmada correctamente"
```

---

## 🎯 Flujo de Completar Experiencia (Dos Pasos)

```mermaid
sequenceDiagram
    actor G as Guía
    actor T as Turista
    participant BE as Backend
    participant DB as Base de Datos
    
    Note over G,T: Estado actual: in_progress
    
    G->>BE: PATCH /bookings/{id}/status (status: completed)
    BE->>DB: UPDATE guide_confirmed_completed = true
    BE->>G: "Has confirmado finalización"
    
    Note over DB: Estado sigue siendo in_progress
    
    T->>BE: PATCH /bookings/{id}/status (status: completed)
    BE->>DB: UPDATE tourist_confirmed_completed = true
    
    alt Ambos confirmaron
        BE->>DB: UPDATE status = 'completed'
        BE->>T: 📧 "Experiencia completada - Escribe reseña"
        BE->>G: 📧 "Experiencia completada"
        BE->>T: "¡Experiencia completada! Ya puedes reseñar"
    else Solo turista confirmó
        BE->>T: "Esperando confirmación del guía"
    end
```

---

## ❌ Flujo de Cancelación

```mermaid
sequenceDiagram
    actor U as Usuario (Turista o Guía)
    participant UI as Interfaz
    participant BE as Backend
    participant DB as Base de Datos
    participant O as Otro Usuario
    
    U->>UI: Click en "Cancelar"
    UI->>U: Modal de confirmación
    U->>UI: Confirma cancelación
    
    UI->>BE: PATCH /bookings/{id}/status (status: cancelled)
    
    BE->>BE: Validar permisos
    BE->>BE: Validar estado != completed
    
    BE->>DB: Iniciar transacción
    BE->>DB: Obtener booking y slot
    BE->>DB: INCREMENT available_spots
    BE->>DB: UPDATE booking status = 'cancelled'
    BE->>DB: Commit transacción
    
    BE->>BE: Procesar reembolso
    BE->>O: 📧 Notificación de cancelación
    BE->>UI: Éxito
    UI->>U: "Reserva cancelada. Cupos devueltos."
```

---

## 🔐 Diagrama de Permisos

```mermaid
flowchart TD
    Start[Acción sobre Booking] --> CheckAuth{¿Autenticado?}
    
    CheckAuth -->|No| Forbidden[403 Forbidden]
    CheckAuth -->|Sí| CheckRole{¿Cuál rol?}
    
    CheckRole -->|Turista| IsTourist{¿Es su reserva?}
    CheckRole -->|Guía| IsGuide{¿Es su experiencia?}
    CheckRole -->|Otro| Forbidden
    
    IsTourist -->|No| Forbidden
    IsTourist -->|Sí| CheckActionT{¿Qué acción?}
    
    IsGuide -->|No| Forbidden
    IsGuide -->|Sí| CheckActionG{¿Qué acción?}
    
    CheckActionT -->|Cancelar| ValidateStateT{Estado != completed?}
    CheckActionT -->|Completar| ValidateInProgress1{Estado = in_progress?}
    CheckActionT -->|Confirmar| Forbidden
    CheckActionT -->|Iniciar| Forbidden
    
    CheckActionG -->|Confirmar| ValidateStatePending{Estado = pending?}
    CheckActionG -->|Iniciar| ValidateStateConfirmed{Estado = confirmed?}
    CheckActionG -->|Completar| ValidateInProgress2{Estado = in_progress?}
    CheckActionG -->|Cancelar| ValidateStateG{Estado != completed?}
    
    ValidateStateT -->|Sí| Allow[✅ Permitido]
    ValidateStateT -->|No| Forbidden
    ValidateStateG -->|Sí| Allow
    ValidateStateG -->|No| Forbidden
    ValidateInProgress1 -->|Sí| Allow
    ValidateInProgress1 -->|No| Forbidden
    ValidateInProgress2 -->|Sí| Allow
    ValidateInProgress2 -->|No| Forbidden
    ValidateStatePending -->|Sí| Allow
    ValidateStatePending -->|No| Forbidden
    ValidateStateConfirmed -->|Sí| Allow
    ValidateStateConfirmed -->|No| Forbidden
```

---

## 🏗️ Arquitectura de Componentes

```mermaid
graph TB
    subgraph Frontend
        UI[Interfaz Usuario]
        Forms[Formularios]
        Lists[Listas de Reservas]
        Badges[Status Badges]
    end
    
    subgraph Backend
        Routes[routes/web.php]
        Controller[BookingController]
        StateMachine[BookingStateMachine]
        Validators[Validadores]
    end
    
    subgraph Models
        Booking[Booking Model]
        Slot[AvailabilitySlot Model]
        Experience[Experience Model]
        User[User Model]
    end
    
    subgraph Database
        BookingsTable[(bookings table)]
        SlotsTable[(availability_slots table)]
    end
    
    subgraph Services
        Payment[Payment Service]
        Notifications[Notification Service]
        Logging[Logging Service]
    end
    
    UI --> Forms
    UI --> Lists
    Lists --> Badges
    
    Forms --> Routes
    Routes --> Controller
    Controller --> StateMachine
    Controller --> Validators
    
    StateMachine --> Booking
    Booking --> BookingsTable
    Slot --> SlotsTable
    
    Controller --> Payment
    Controller --> Notifications
    Controller --> Logging
    
    Booking -.-> Slot
    Booking -.-> Experience
    Booking -.-> User
```

---

## 📱 Flujo de Usuario Turista Completo

```mermaid
journey
    title Experiencia del Turista en NexLocal
    section Descubrir
      Navegar experiencias: 5: Turista
      Ver detalles: 5: Turista
      Revisar reseñas: 4: Turista
    section Reservar
      Seleccionar fecha: 5: Turista
      Ingresar viajeros: 5: Turista
      Procesar pago: 3: Turista
      Recibir confirmación: 5: Turista
    section Esperar
      Ver estado pending: 3: Turista
      Recibir confirmación guía: 5: Turista
    section Experimentar
      Asistir a experiencia: 5: Turista
      Guía inicia experiencia: 5: Turista, Guía
      Disfrutar actividad: 5: Turista
    section Finalizar
      Confirmar finalización: 5: Turista
      Escribir reseña: 4: Turista
      Valorar experiencia: 5: Turista
```

---

## 🎯 Flujo de Usuario Guía Completo

```mermaid
journey
    title Experiencia del Guía en NexLocal
    section Crear
      Publicar experiencia: 5: Guía
      Configurar horarios: 4: Guía
      Definir cupos: 5: Guía
    section Recibir
      Notificación nueva reserva: 5: Guía
      Revisar detalles: 5: Guía
      Verificar disponibilidad: 4: Guía
    section Confirmar
      Confirmar reserva: 5: Guía
      Contactar turista: 4: Guía
    section Ejecutar
      Preparar experiencia: 5: Guía
      Iniciar experiencia: 5: Guía
      Guiar actividad: 5: Guía
    section Finalizar
      Confirmar finalización: 5: Guía
      Revisar reseña recibida: 4: Guía
      Mejorar para próximas: 5: Guía
```

---

## 🔍 Flujo de Validación de Cupos

```mermaid
flowchart TD
    Start[Intento de Reserva] --> Lock[DB Transaction + lockForUpdate]
    Lock --> GetSlot[Obtener AvailabilitySlot]
    GetSlot --> CheckSpots{available_spots >= num_travelers?}
    
    CheckSpots -->|No| Error1[Error: Sin cupos suficientes]
    CheckSpots -->|Sí| CheckDate{start_time > now?}
    
    CheckDate -->|No| Error2[Error: Fecha pasada]
    CheckDate -->|Sí| CheckDuplicate{¿Reserva duplicada?}
    
    CheckDuplicate -->|Sí| Error3[Error: Ya tienes reserva]
    CheckDuplicate -->|No| ProcessPayment[Procesar Pago]
    
    ProcessPayment -->|Fallo| Error4[Error: Pago fallido]
    ProcessPayment -->|Éxito| CreateBooking[Crear Booking]
    
    CreateBooking --> Decrement[Decrementar available_spots]
    Decrement --> Commit[Commit Transaction]
    Commit --> Notify[Notificar Guía]
    Notify --> Success[✅ Reserva Creada]
    
    Error1 --> Rollback[Rollback]
    Error2 --> Rollback
    Error3 --> Rollback
    Error4 --> Rollback
    Rollback --> End[Fin]
    Success --> End
```

---

## 📊 Timeline de una Reserva Típica

```mermaid
gantt
    title Timeline de Reserva - Tour por el Río Sinú
    dateFormat YYYY-MM-DD HH:mm
    axisFormat %d/%m %H:%M
    
    section Turista
    Navega y selecciona :done, t1, 2025-11-16 10:00, 30m
    Completa reserva :done, t2, after t1, 10m
    Espera confirmación :active, t3, after t2, 12h
    Asiste a experiencia :milestone, t4, 2025-11-18 09:00, 0m
    Confirma finalización :crit, t5, 2025-11-18 13:00, 5m
    Escribe reseña :t6, after t5, 1h
    
    section Guía
    Recibe notificación :done, g1, 2025-11-16 10:10, 1m
    Revisa reserva :g2, after g1, 30m
    Confirma reserva :milestone, g3, 2025-11-16 14:00, 0m
    Prepara experiencia :g4, 2025-11-18 08:00, 1h
    Inicia experiencia :milestone, g5, 2025-11-18 09:00, 0m
    Guía actividad :g6, after g5, 4h
    Confirma finalización :crit, g7, 2025-11-18 13:05, 5m
    
    section Sistema
    Status: pending :active, s1, 2025-11-16 10:10, 4h
    Status: confirmed :active, s2, 2025-11-16 14:00, 43h
    Status: in_progress :active, s3, 2025-11-18 09:00, 4h
    Status: completed :done, s4, 2025-11-18 13:05, 0m
```

---

## 🔄 Ciclo de Vida Completo

```mermaid
graph LR
    subgraph Creación
        A[Turista selecciona] --> B[Completa datos]
        B --> C[Procesa pago]
        C --> D[pending]
    end
    
    subgraph Confirmación
        D --> E{Guía decide}
        E -->|Acepta| F[confirmed]
        E -->|Rechaza| Z1[cancelled]
    end
    
    subgraph Ejecución
        F --> G[Fecha llega]
        G --> H[Guía inicia]
        H --> I[in_progress]
    end
    
    subgraph Finalización
        I --> J{Ambos confirman?}
        J -->|Sí| K[completed]
        J -->|No| I
    end
    
    subgraph Post-Experiencia
        K --> L[Reseña habilitada]
        L --> M[Turista reseña]
        M --> N[Fin]
    end
    
    subgraph Cancelación
        D -.->|Cancelar| Z1
        F -.->|Cancelar| Z2[cancelled]
        I -.->|Cancelar| Z3[cancelled]
        Z1 --> Z[Cupos devueltos]
        Z2 --> Z
        Z3 --> Z
        Z --> ZZ[Fin]
    end
    
    style D fill:#ffd700
    style F fill:#90ee90
    style I fill:#87ceeb
    style K fill:#98fb98
    style Z1 fill:#ffcccb
    style Z2 fill:#ffcccb
    style Z3 fill:#ffcccb
```

---

## 📝 Cómo Usar Estos Diagramas

### Visualización
- **GitHub/GitLab**: Se renderizan automáticamente
- **VS Code**: Instalar extensión "Markdown Preview Mermaid Support"
- **Editor online**: https://mermaid.live/

### Exportar
Para exportar a imagen:
1. Visitar https://mermaid.live/
2. Pegar el código del diagrama
3. Click en "Actions" > "PNG" o "SVG"

### Personalizar
Puedes modificar estos diagramas según tus necesidades editando el código Mermaid.

---

**Última actualización:** Noviembre 2025  
**Documentación:** [Ver README principal](./README.md)

