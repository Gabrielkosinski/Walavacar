#!/bin/sh
set -e

# Inicia PHP-FPM em background
php-fpm &

# Aguarda PHP-FPM estar pronto
sleep 2

# Testa se PHP-FPM está respondendo
until nc -z 127.0.0.1 9000; do
  echo "Aguardando PHP-FPM..."
  sleep 1
done

echo "PHP-FPM pronto! Iniciando Nginx..."

# Inicia o Nginx em foreground
nginx -g 'daemon off;'
