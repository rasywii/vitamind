#!/usr/bin/env sh
set -e

# Cachea configuracion y vistas para produccion.
# (No cacheamos rutas porque la ruta "/" usa un closure y route:cache fallaria.)
php artisan config:cache
php artisan view:cache

# La base SQLite ya viene con datos en la imagen; --force ejecuta migraciones
# pendientes sin pedir confirmacion. Si no hay nada que migrar, no pasa nada.
php artisan migrate --force || true

# Enlace de storage publico (por si algun upload lo necesita).
php artisan storage:link || true

# Arranca el servidor en el puerto que asigna Render.
exec php artisan serve --host 0.0.0.0 --port "${PORT:-10000}"