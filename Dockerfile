# ==========================================
# Dockerfile para Laravel + Vite en Render
# ==========================================
FROM php:8.2-cli-alpine

# Instalar dependencias del sistema y librerías necesarias para extensiones PHP y Node.js
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    nodejs \
    npm \
    git \
    curl \
    bash

# Configurar e instalar extensiones de PHP requeridas por Laravel y PostgreSQL
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        zip \
        bcmath \
        gd \
        opcache \
        pcntl

# Instalar Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www

# Copiar archivos de dependencias de Composer y Node.js primero para aprovechar el caché de capas
COPY composer.json composer.lock package.json package-lock.json ./

# Instalar dependencias de PHP (sin scripts para evitar fallos antes de tener el código fuente)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs

# Instalar dependencias de Node.js
RUN npm ci || npm install

# Copiar todo el código fuente del proyecto
COPY . .

# Generar el autoloader optimizado de Composer
RUN composer dump-autoload --optimize --no-dev

# Compilar assets de frontend (Tailwind CSS y Vite)
RUN npm run build

# Eliminar node_modules para reducir el tamaño final de la imagen Docker
RUN rm -rf node_modules

# Ajustar permisos para storage y bootstrap/cache
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Configurar script de inicio
RUN chmod +x /var/www/docker-entrypoint.sh \
    && sed -i 's/\r$//' /var/www/docker-entrypoint.sh

# Exponer el puerto predeterminado (Render inyecta la variable $PORT en tiempo de ejecución)
EXPOSE 10000

# Definir el script de entrada
ENTRYPOINT ["/var/www/docker-entrypoint.sh"]
