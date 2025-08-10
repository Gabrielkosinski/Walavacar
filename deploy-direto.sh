#!/bin/bash

# 🚀 DEPLOY DIRETO - WaLavacar Railway
# Usando as variáveis fornecidas pelo PostgreSQL

echo "🚀 Configurando WaLavacar no Railway com PostgreSQL..."

# Configurar todas as variáveis de uma vez
railway variables set APP_NAME=WaLavacar \
APP_ENV=production \
APP_DEBUG=false \
APP_KEY=base64:Z25fAg7WjdId3TapOdGQ8+kyl8klzgZ3/DpWQ1ZI9DU= \
APP_LOCALE=pt_BR \
APP_FALLBACK_LOCALE=pt_BR \
APP_FAKER_LOCALE=pt_BR \
"DB_CONNECTION=pgsql" \
"DB_HOST=\${{RAILWAY_PRIVATE_DOMAIN}}" \
"DB_PORT=5432" \
"DB_DATABASE=railway" \
"DB_USERNAME=postgres" \
"DB_PASSWORD=\${{POSTGRES_PASSWORD}}" \
LOG_CHANNEL=single \
LOG_LEVEL=error \
SESSION_DRIVER=database \
CACHE_STORE=file \
QUEUE_CONNECTION=sync \
WHATSAPP_BUSINESS_NUMBER=5541996875650

echo "✅ Variáveis configuradas!"
echo "🗄️ Executando migrations..."

# Executar migrations
railway run php artisan migrate --force

echo "🎉 Deploy concluído!"
echo "🌐 URL da aplicação:"
railway domain

echo ""
echo "📋 Login:"
echo "Email: admin@admin.com"
echo "Senha: admin123"
