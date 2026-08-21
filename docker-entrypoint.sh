#!/bin/sh
set -e

echo "==> Iniciando proceso de despliegue en Render..."

# 1. Crear enlace simbólico de almacenamiento público si no existe
echo "==> Creando storage:link..."
php artisan storage:link --force || true

# 2. Optimización y caché de Laravel
echo "==> Optimizando configuraciones y vistas..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Ejecución de migraciones automáticas en la base de datos PostgreSQL
echo "==> Ejecutando migraciones de base de datos..."
php artisan migrate --force

# 4. Iniciar el servidor web escuchando en el puerto asignado por Render ($PORT)
PORT="${PORT:-10000}"
echo "==> Servidor Laravel iniciado en el puerto $PORT..."
exec php artisan serve --host=0.0.0.0 --port="$PORT"
