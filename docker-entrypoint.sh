#!/bin/sh
set -e

# Inicia PHP-FPM em background
php-fpm &

# Inicia o Nginx em foreground
nginx -g 'daemon off;'
