#!/bin/sh
set -e

# Substitui a variável PORT no nginx.conf
envsubst '${PORT}' < /etc/nginx/nginx.conf > /tmp/nginx.conf && mv /tmp/nginx.conf /etc/nginx/nginx.conf

# Limpa cache do Laravel e gera chave se necessário
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Gera chave da aplicação se não existir
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Inicia PHP-FPM em background
php-fpm &

# Aguarda PHP-FPM estar pronto
sleep 2

# Testa se PHP-FPM está respondendo
until nc -z 127.0.0.1 9000; do
  echo "Aguardando PHP-FPM..."
  sleep 1
done

echo "PHP-FPM pronto! Iniciando Nginx na porta $PORT..."

# Inicia o Nginx em foreground
nginx -g 'daemon off;'
