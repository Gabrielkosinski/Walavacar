#!/bin/bash

# 🚀 Deploy Script para Railway - WaLavacar
# Este script prepara a aplicação para deploy no Railway

echo "🚀 Iniciando deploy para Railway..."

# 1. Limpar cache de desenvolvimento
echo "🧹 Limpando cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 2. Instalar dependências de produção
echo "📦 Instalando dependências PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# 3. Build dos assets frontend
echo "🎨 Compilando assets frontend..."
npm ci --only=production
npm run build

# 4. Otimizações para produção
echo "⚡ Aplicando otimizações..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Criar banco SQLite se não existir
echo "🗄️ Preparando banco de dados..."
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
fi

# 6. Executar migrações
echo "🔄 Executando migrações..."
php artisan migrate --force

# 7. Definir permissões
echo "🔒 Configurando permissões..."
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs
chmod -R 777 storage/framework
chmod -R 777 storage/app

echo "✅ Deploy preparado com sucesso!"
echo "📝 Variáveis necessárias no Railway:"
echo "   - APP_KEY: $(php artisan --version > /dev/null && grep APP_KEY .env.railway | cut -d'=' -f2)"
echo "   - APP_URL: https://seuapp.railway.app"
echo "   - APP_ENV: production"
echo "   - APP_DEBUG: false"

echo ""
echo "🎯 Próximos passos:"
echo "1. Fazer push para o repositório Git"
echo "2. Conectar repositório no Railway"
echo "3. Configurar variáveis de ambiente"
echo "4. Deploy automático será executado"
