#!/bin/bash

# 🔧 RESET COMPLETO MIGRATIONS - Railway
echo "🧹 RESET COMPLETO DE MIGRATIONS NO RAILWAY"
echo "=========================================="

echo ""
echo "⚠️  ATENÇÃO: Isso vai resetar TODAS as migrations!"
echo "🗃️  Todos os dados serão perdidos!"
echo ""

echo "📋 COMANDOS PARA EXECUTAR NO RAILWAY:"
echo ""

echo "1️⃣ RESET COMPLETO DO BANCO:"
echo "railway run php artisan migrate:fresh --force"
echo ""

echo "2️⃣ EXECUTAR SEEDERS:"
echo "railway run php artisan db:seed --force"
echo ""

echo "3️⃣ CRIAR USUÁRIO ADMIN:"
echo "railway run php artisan db:seed --class=CreateAdminUserSeeder --force"
echo ""

echo "4️⃣ VERIFICAR STATUS:"
echo "railway run php artisan migrate:status"
echo ""

echo "5️⃣ VER URL:"
echo "railway domain"
echo ""

echo "✅ LOGIN APÓS RESET:"
echo "📧 Email: admin@admin.com"
echo "🔐 Senha: admin123"
echo ""

echo "🎯 ALTERNATIVA MAIS SEGURA (se não quiser perder dados):"
echo "railway run php artisan migrate --pretend"
echo "# Para ver o que seria executado sem executar"
