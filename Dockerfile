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
RUN chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache

# Instale o Nginx
RUN apt-get update && apt-get install -y nginx

# Adicione configuração básica do Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Script de entrypoint para rodar PHP-FPM e Nginx juntos
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
