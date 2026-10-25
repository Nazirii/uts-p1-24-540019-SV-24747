#!/bin/sh
set -e

echo "[entrypoint] Menunggu database ${DB_HOST}:${DB_PORT} siap..."
i=0
until php artisan migrate --force >/dev/null 2>&1; do
  i=$((i+1))
  if [ "$i" -ge 30 ]; then
    echo "[entrypoint] Database tidak bisa dihubungi, berhenti."
    php artisan migrate --force
    exit 1
  fi
  sleep 2
done
echo "[entrypoint] Migrasi selesai."

php artisan db:seed --force
php artisan config:cache

echo "[entrypoint] Menjalankan: $*"
exec "$@"
