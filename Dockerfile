# Etapa 1: Build dos assets com Node.js
FROM node:20 AS nodebuild
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa 2: PHP + Composer
FROM php:8.2-cli
WORKDIR /app
COPY --from=nodebuild /app /app

# Instale dependências do PHP conforme necessário
RUN apt-get update \
    && apt-get install -y unzip git libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
