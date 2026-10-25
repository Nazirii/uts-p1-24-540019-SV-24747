#!/usr/bin/env bash
# Melengkapi folder backend/ dengan skeleton Laravel 12 + Sanctum.
# File buatan kita (controller, model, migrasi, test, dll.) TIDAK ditimpa.
# Jalankan SEKALI dari root repo:  bash scripts/setup-backend.sh
set -euo pipefail

cd "$(dirname "$0")/.."

if [ -f backend/artisan ]; then
  echo "backend/ sudah lengkap (artisan ditemukan). Lewati."
  exit 0
fi

echo ">> Download skeleton Laravel 12 ke _laravel_tmp/ ..."
rm -rf _laravel_tmp
composer create-project laravel/laravel:^12.0 _laravel_tmp --prefer-dist --no-interaction

echo ">> Gabungkan ke backend/ (tanpa menimpa file yang sudah ada) ..."
cp -rn _laravel_tmp/. backend/
rm -rf _laravel_tmp

cd backend
echo ">> Pasang Laravel Sanctum ..."
composer require laravel/sanctum:^4.0 --no-interaction

# .env lokal untuk development (tidak di-commit)
[ -f .env ] || cp .env.example .env
php artisan key:generate --force
touch database/database.sqlite
php artisan migrate:fresh --seed --force

echo ">> Jalankan test ..."
php artisan test

echo ""
echo "Selesai. Backend siap. Jalankan lokal dengan: cd backend && php artisan serve"
