#!/bin/bash

# 🔒 CORRIGIR MIXED CONTENT - HTTPS Railway
echo "🔒 Corrigindo Mixed Content no Railway..."

echo "📋 EXECUTE ESTES COMANDOS NO RAILWAY:"
echo ""

echo "1️⃣ CONFIGURAR APP_URL COM HTTPS:"
echo "railway variables set APP_URL=https://web-production-9622.up.railway.app"
echo ""

echo "2️⃣ FORÇAR HTTPS NOS ASSETS:"
echo "railway variables set ASSET_URL=https://web-production-9622.up.railway.app"
echo ""

echo "3️⃣ CONFIGURAR PROXY CONFIÁVEL:"
echo "railway variables set TRUSTED_PROXIES=*"
echo ""

echo "4️⃣ LIMPAR CACHE:"
echo "railway run php artisan config:clear"
echo "railway run php artisan cache:clear"
echo ""

echo "5️⃣ GERAR CACHE DE CONFIGURAÇÃO:"
echo "railway run php artisan config:cache"
echo ""

echo "6️⃣ VERIFICAR SE RESOLVEU:"
echo "railway domain"
echo "# Acesse a URL e verifique se não há mais erros de Mixed Content"
echo ""

echo "✅ CONFIGURAÇÕES APLICADAS:"
echo "🔗 APP_URL: https://web-production-9622.up.railway.app"
echo "🔗 ASSET_URL: https://web-production-9622.up.railway.app"
echo "🛡️ TRUSTED_PROXIES: *"
echo ""

echo "📱 TESTE:"
echo "1. Acesse: https://web-production-9622.up.railway.app"
echo "2. Abra DevTools (F12)"
echo "3. Verifique se não há mais erros de Mixed Content"
