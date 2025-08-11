#!/bin/bash

echo "🔍 Verificando criação do usuário admin..."
echo "📧 Email: admin@admin.com"
echo "🔑 Senha: admin123"
echo ""

# Verifica se o usuário admin existe via API (se disponível)
echo "🌐 Verificando via aplicação web..."
echo "1. Acesse: https://walavacar-production.up.railway.app/login"
echo "2. Use as credenciais:"
echo "   Email: admin@admin.com"
echo "   Senha: admin123"
echo ""

echo "✅ Se o login funcionar, o usuário admin foi criado com sucesso!"
echo "❌ Se der erro 'credenciais não encontradas', a migração ainda não foi executada."
echo ""
echo "🔄 Para forçar execução da migração:"
echo "   1. Acesse o painel do Railway"
echo "   2. Vá em 'Deployments' do seu projeto"
echo "   3. Verifique se o deploy mais recente foi bem-sucedido"
echo "   4. Se necessário, execute: php artisan migrate --force"
