# 🔧 Troubleshooting - Sistema de Verificación

## Problemas Comunes y Soluciones

### 1. ❌ Error: "Disk [private] does not have a configured driver"

**Síntoma:**
```
InvalidArgumentException
Disk [private] does not have a configured driver.
```

**Causa:**  
El disco 'private' no está configurado en `config/filesystems.php`

**Solución:**  
✅ Ya resuelto. El disco 'private' está configurado correctamente.

Si vuelves a verlo:
1. Verifica que existe en `config/filesystems.php`
2. Ejecuta `php artisan config:clear`
3. Reinicia el servidor

---

### 2. ❌ Error: "The file could not be stored"

**Síntoma:**
El archivo no se guarda o da error de permisos

**Solución:**
```bash
# Dar permisos al directorio storage
php artisan storage:link
chmod -R 775 storage
chown -R www-data:www-data storage  # En Linux
```

En Windows (PowerShell como Admin):
```powershell
icacls storage /grant Everyone:(OI)(CI)F /T
```

---

### 3. ❌ Los documentos no se pueden descargar

**Síntoma:**
Al hacer clic en "Ver/Descargar" da error 404 o archivo no encontrado

**Causa:**
El archivo no existe o la ruta es incorrecta

**Solución:**
1. Verifica que el archivo existe:
   ```bash
   ls storage/app/private/identity-documents/
   ```
2. Verifica el path en la base de datos:
   ```sql
   SELECT id, name, identity_document_path FROM users WHERE role = 'guide';
   ```
3. El path debe ser relativo: `identity-documents/{user_id}_front.jpg`

---

### 4. ❌ Error: "Class NotificationHelper not found"

**Síntoma:**
```
Class "App\Helpers\NotificationHelper" not found
```

**Solución:**
Verifica que existe el archivo:
```bash
app/Helpers/NotificationHelper.php
```

Si no existe, créalo o comenta las líneas de notificación temporalmente.

---

### 4.1 ❌ Error: "Call to undefined method NotificationHelper::create()"

**Síntoma:**
```
Call to undefined method App\Helpers\NotificationHelper::create()
```

**Causa:**  
El método `create()` no existía en NotificationHelper (ya resuelto)

**Solución (Ya Aplicada):**  
✅ Se agregó el método `create()` en `app/Helpers/NotificationHelper.php`

**Si vuelve a ocurrir:**
1. Verifica que el método `create()` existe en el archivo
2. Ejecuta `php artisan optimize:clear`
3. Reinicia el servidor
4. Verifica con: 
   ```bash
   php artisan tinker --execute="echo method_exists('App\Helpers\NotificationHelper', 'create') ? 'OK' : 'ERROR';"
   ```

---

### 5. ❌ El middleware 'verified.guide' no funciona

**Síntoma:**
Guías no verificados pueden crear experiencias

**Solución:**
1. Verifica que el middleware está registrado en `bootstrap/app.php`
2. Verifica que las rutas tienen el middleware:
   ```php
   Route::resource('experiences', ...)
       ->middleware('verified.guide');
   ```
3. Limpia caché: `php artisan route:clear`

---

### 6. ❌ El formulario no acepta archivos grandes

**Síntoma:**
"The file may not be greater than 2048 kilobytes"

**Solución:**
Ajustar límites en `php.ini`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

Y en `.htaccess` (si usas Apache):
```apache
php_value upload_max_filesize 10M
php_value post_max_size 10M
```

Luego reinicia el servidor:
```bash
php artisan serve  # Reiniciar
```

---

### 7. ❌ Las notificaciones no llegan

**Síntoma:**
Admin o guía no reciben notificaciones

**Solución:**
1. Verifica que `NotificationHelper::create()` existe y funciona
2. Revisa la tabla de notificaciones:
   ```sql
   SELECT * FROM notifications ORDER BY created_at DESC LIMIT 10;
   ```
3. Verifica que el usuario tiene notificaciones activadas
4. Revisa `app/Helpers/NotificationHelper.php`

---

### 8. ❌ Error 500 al enviar formulario

**Síntoma:**
Error genérico al enviar el formulario de verificación

**Solución:**
1. Revisa los logs de Laravel:
   ```bash
   tail -f storage/logs/laravel.log
   ```
2. Verifica la validación:
   - Formatos permitidos: JPG, JPEG, PNG, PDF
   - Tamaño máximo: 5MB
3. Verifica que ambos campos están presentes:
   - `identity_document_front`
   - `identity_document_back`

---

### 9. ❌ Los documentos se guardan pero no se ven en admin

**Síntoma:**
El guía aparece en la lista pero los documentos no se pueden ver

**Solución:**
1. Verifica la consulta en `AdminController@verificationQueue`:
   ```php
   User::where('verification_status', 'pending')
   ```
2. Verifica que el usuario tiene `verification_status = 'pending'`
3. Verifica que las rutas de descarga son correctas

---

### 10. ❌ El dashboard no muestra la alerta de verificación

**Síntoma:**
Guía no verificado no ve mensaje de advertencia

**Solución:**
1. Verifica el método `isVerifiedGuide()` en el modelo User
2. Verifica que el blade usa:
   ```php
   @if(!Auth::user()->isVerifiedGuide())
   ```
3. Limpia caché de vistas:
   ```bash
   php artisan view:clear
   ```

---

## 🔍 Comandos Útiles de Diagnóstico

### Verificar Configuración del Disco
```bash
php artisan tinker
Storage::disk('private')->exists('test')
```

### Ver Estado de Verificaciones
```sql
SELECT 
    id, 
    name, 
    role, 
    verification_status, 
    identity_verified_at 
FROM users 
WHERE role = 'guide';
```

### Limpiar Todas las Cachés
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Ver Rutas del Sistema
```bash
php artisan route:list | findstr verification
```

### Ver Permisos del Storage
```bash
ls -la storage/app/private/identity-documents/
```

---

## 📞 Si Nada Funciona

1. **Revisa los logs:**
   ```bash
   storage/logs/laravel.log
   ```

2. **Modo debug:**
   En `.env` asegúrate de tener:
   ```env
   APP_DEBUG=true
   ```

3. **Revisa la documentación:**
   - `docs/SISTEMA_VERIFICACION_GUIAS.md`
   - `docs/GUIA_USO_VERIFICACION.md`

4. **Reinicia todo:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   php artisan serve
   ```

---

## ✅ Checklist de Verificación

- [ ] Disco 'private' configurado en `config/filesystems.php`
- [ ] Directorio `storage/app/private/identity-documents/` existe
- [ ] Permisos correctos en `storage/` (775)
- [ ] Middleware registrado en `bootstrap/app.php`
- [ ] Rutas protegidas con `verified.guide`
- [ ] Migración ejecutada correctamente
- [ ] NotificationHelper existe y funciona
- [ ] Variables de entorno correctas en `.env`

---

## 🎯 Estado Actual del Sistema

✅ **Disco 'private' configurado**  
✅ **Directorio creado y protegido**  
✅ **Sistema funcional**  

Todo está listo para usar el sistema de verificación. Si encuentras algún problema, consulta esta guía primero.

