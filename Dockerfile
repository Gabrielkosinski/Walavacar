# Etapa 1: Build dos assets com Node.js
FROM node:20 AS nodebuild
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM php:8.2-fpm
WORKDIR /app
COPY --from=nodebuild /app /app

# Instale dependências do PHP conforme necessário
RUN apt-get update \
    && apt-get install -y unzip git libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm"]
