# =========================================================
#  Etapa 1 — Compilar assets del frontend (Vite + Tailwind)
# =========================================================
FROM node:20 AS assets
WORKDIR /app

# Solo lo necesario para instalar dependencias y compilar
COPY package.json vite.config.js ./
RUN npm install

COPY resources ./resources
COPY public ./public

# Genera public/build (manifest.json + assets + fuentes)
RUN npm run build


# =========================================================
#  Etapa 2 — Aplicacion PHP (Laravel + Filament)
# =========================================================
FROM php:8.3-cli

# Dependencias del sistema + extensiones PHP que usan Laravel y Filament
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libicu-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" pdo_sqlite zip intl gd bcmath exif \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copiamos el proyecto completo (incluye database/database.sqlite con los datos)
COPY . .

# Traemos los assets ya compilados de la etapa anterior
COPY --from=assets /app/public/build ./public/build

# Dependencias PHP de produccion
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Permisos de escritura para Laravel
RUN chmod -R 775 storage bootstrap/cache

# Permite que el servidor embebido atienda varias peticiones a la vez
# (evita que la pagina se quede colgada al cargar varios assets).
ENV PHP_CLI_SERVER_WORKERS=4

# Render asigna el puerto via $PORT (por defecto 10000)
EXPOSE 10000

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

CMD ["/usr/local/bin/entrypoint.sh"]