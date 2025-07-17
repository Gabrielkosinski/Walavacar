#!/bin/bash

# 🔍 Debug Script para Railway - WaLavacar
echo "🔍 INICIANDO DEBUG RAILWAY..."

# Verificar variáveis críticas
echo "📋 VARIÁVEIS DE AMBIENTE:"
echo "APP_ENV: $APP_ENV"
echo "APP_KEY existe: $([ -n "$APP_KEY" ] && echo "SIM" || echo "NÃO")"
echo "DB_CONNECTION: $DB_CONNECTION"
echo "PORT: $PORT"
echo ""

# Verificar estrutura de arquivos
echo "📁 ESTRUTURA DE ARQUIVOS:"
echo "Directory atual: $(pwd)"
echo "Arquivo .env existe: $([ -f .env ] && echo "SIM" || echo "NÃO")"
echo "Directory database existe: $([ -d database ] && echo "SIM" || echo "NÃO")"
echo "Arquivo SQLite existe: $([ -f database/database.sqlite ] && echo "SIM" || echo "NÃO")"
echo ""

# Criar estrutura necessária
echo "🔧 CRIANDO ESTRUTURA..."
mkdir -p database
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Criar banco SQLite
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    echo "✅ database.sqlite criado"
else
    echo "✅ database.sqlite já existe"
fi

# Definir permissões
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 database
echo "✅ Permissões definidas"

# Verificar se APP_KEY está definida
if [ -z "$APP_KEY" ]; then
    echo "⚠️ APP_KEY não definida! Gerando uma nova..."
    php artisan key:generate --force
fi

# Testar conexão com banco
echo "🧪 TESTANDO BANCO..."
php artisan migrate --force 2>&1 | head -5

# Testar configuração básica
echo "🧪 TESTANDO CONFIGURAÇÃO..."
php artisan about 2>&1 | head -10

echo "✅ DEBUG CONCLUÍDO - INICIANDO SERVIDOR..."
php artisan serve --host=0.0.0.0 --port=$PORT
