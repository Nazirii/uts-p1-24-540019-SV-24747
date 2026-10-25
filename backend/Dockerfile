# =============================================================
# Backend Laravel + FrankenPHP (soal 5a + bonus soal 7)
# Image dasar memakai versi yang jelas, BUKAN latest.
# =============================================================
FROM dunglas/frankenphp:1.9-php8.3-bookworm

# Ekstensi PHP yang dibutuhkan Laravel + MySQL
RUN install-php-extensions pdo_mysql zip bcmath opcache

# Composer versi jelas
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# 1) Salin composer.json & composer.lock DULU lalu install dependency.
#    Lapisan ini di-cache selama dua file tsb tidak berubah.
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist

# 2) Baru salin kode aplikasi
COPY . .

RUN composer dump-autoload --optimize --no-dev \
    && php artisan package:discover --ansi \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod +x docker/entrypoint.sh

# Tidak ada nilai rahasia di sini. APP_KEY, DB_PASSWORD, dll.
# diberikan lewat environment saat container dijalankan.
ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    SERVER_NAME=:8000

EXPOSE 8000

ENTRYPOINT ["docker/entrypoint.sh"]
# FrankenPHP sebagai server aplikasi (menggantikan php artisan serve / PHP-FPM+Nginx)
CMD ["frankenphp", "php-server", "--listen", ":8000", "--root", "public/", "--access-log"]
