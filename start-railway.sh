#!/bin/bash

# 🚀 Script de Inicialização Railway - WaLavacar
# Este script garante que tudo funcione no Railway

echo "🚀 Iniciando WaLavacar no Railway..."

# 1. Verificar se APP_KEY existe
if [ -z "$APP_KEY" ]; then
    echo "⚠️ APP_KEY não definida, gerando uma nova..."
    php artisan key:generate --force
else
    echo "✅ APP_KEY encontrada"
fi

# 2. Criar diretório de database se não existir
mkdir -p database
echo "📁 Diretório database criado"

# 3. Criar arquivo SQLite se não existir
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    echo "🗄️ Arquivo database.sqlite criado"
else
    echo "✅ Database SQLite encontrado"
fi

# 4. Definir permissões
chmod 755 database
chmod 666 database/database.sqlite
chmod -R 755 storage
chmod -R 755 bootstrap/cache
echo "🔒 Permissões configuradas"

# 5. Executar migrações
echo "🔄 Executando migrações..."
php artisan migrate --force

# 6. Cache de configurações
echo "⚡ Configurando cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Verificar se tudo está funcionando
echo "🧪 Testando configuração..."
php artisan about --only=Application

echo "✅ WaLavacar iniciado com sucesso!"

# 8. Iniciar servidor
echo "🌐 Iniciando servidor na porta $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT
