#!/bin/bash

# 🚀 COMANDOS PARA DEPLOY NO RA# Banco de dados:
echo "railway variables set DB_CONNECTION=pgsql"
echo 'railway variables set "DB_HOST=${{RAILWAY_PRIVATE_DOMAIN}}"'
echo "railway variables set DB_PORT=5432"
echo "railway variables set DB_DATABASE=railway"
echo "railway variables set DB_USERNAME=postgres"
echo 'railway variables set "DB_PASSWORD=${{POSTGRES_PASSWORD}}"' WaLavacar
# ==============================================

echo "🚀 DEPLOY DO WALAVACAR NO RAILWAY"
echo "================================="

# PASSO 1: Login no Railway (se não estiver logado)
echo ""
echo "📋 PASSO 1: LOGIN NO RAILWAY"
echo "railway login"

# PASSO 2: Conectar ao projeto ou criar novo
echo ""
echo "📋 PASSO 2: CONECTAR AO PROJETO"
echo "# Se já tem projeto:"
echo "railway link"
echo ""
echo "# OU criar novo projeto:"
echo "railway init"

# PASSO 3: Adicionar PostgreSQL
echo ""
echo "📋 PASSO 3: ADICIONAR POSTGRESQL"
echo "railway add postgresql"

# PASSO 4: Configurar variáveis de ambiente
echo ""
echo "📋 PASSO 4: CONFIGURAR VARIÁVEIS"
echo ""
echo "# Gerar APP_KEY:"
echo "railway run php artisan key:generate --show"
echo "# (Copie a chave gerada)"
echo ""
echo "# Configurar todas as variáveis:"
echo "railway variables set APP_NAME=WaLavacar"
echo "railway variables set APP_ENV=production"
echo "railway variables set APP_DEBUG=false"
echo "railway variables set APP_KEY=base64:SUA_CHAVE_AQUI"
echo "railway variables set APP_LOCALE=pt_BR"
echo "railway variables set APP_FALLBACK_LOCALE=pt_BR"
echo "railway variables set APP_FAKER_LOCALE=pt_BR"
echo ""
echo "# Banco de dados:"
echo "railway variables set DB_CONNECTION=pgsql"
echo "railway variables set DB_HOST=postgres.railway.internal"
echo "railway variables set DB_PORT=5432"
echo "railway variables set DB_DATABASE=railway"
echo "railway variables set DB_USERNAME=postgres"
echo 'railway variables set "DB_PASSWORD=${{PostgreSQL.DATABASE_URL}}"'
echo ""
echo "# Cache e sessões:"
echo "railway variables set LOG_CHANNEL=single"
echo "railway variables set LOG_LEVEL=error"
echo "railway variables set SESSION_DRIVER=database"
echo "railway variables set CACHE_STORE=file"
echo "railway variables set QUEUE_CONNECTION=sync"
echo ""
echo "# WhatsApp:"
echo "railway variables set WHATSAPP_BUSINESS_NUMBER=5541996875650"

# PASSO 5: Deploy automático
echo ""
echo "📋 PASSO 5: DEPLOY AUTOMÁTICO"
echo "# O Railway detecta mudanças no GitHub automaticamente"
echo "# Apenas faça push:"
echo "git push"

# PASSO 6: Executar migrations
echo ""
echo "📋 PASSO 6: EXECUTAR MIGRATIONS"
echo "# Após o deploy, execute:"
echo "railway run php artisan migrate --force"

# PASSO 7: Verificar status
echo ""
echo "📋 PASSO 7: VERIFICAR STATUS"
echo "railway status"
echo "railway logs"
echo "railway domain"

# COMANDOS RESUMIDOS
echo ""
echo "🎯 RESUMO - COMANDOS PRINCIPAIS:"
echo "================================="
echo "1. railway login"
echo "2. railway link  # ou railway init"
echo "3. railway add postgresql"
echo "4. # Configurar variáveis (ver acima)"
echo "5. git push"
echo "6. railway run php artisan migrate --force"
echo "7. railway domain  # para ver URL"

echo ""
echo "✅ APÓS DEPLOY, ACESSE:"
echo "📧 Email: admin@admin.com"
echo "🔐 Senha: admin123"
echo ""
