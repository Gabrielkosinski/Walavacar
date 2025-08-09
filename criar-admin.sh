#!/bin/bash

# 🚀 CRIAR USUÁRIO ADMIN NO RAILWAY - WaLavacar
echo "👤 Criando usuário admin no Railway..."

# Comando para criar usuário admin
echo "📋 Execute este comando no Railway:"
echo ""
echo "railway run php artisan db:seed --class=CreateAdminUserSeeder --force"
echo ""

# OU comando direto via tinker
echo "🔧 OU execute este comando alternativo:"
echo ""
echo 'railway run php artisan tinker --execute="App\Models\User::firstOrCreate(['\''email'\'' => '\''admin@admin.com'\''], ['\''name'\'' => '\''Admin WaLavacar'\'', '\''email'\'' => '\''admin@admin.com'\'', '\''password'\'' => bcrypt('\''admin123'\''), '\''tipo'\'' => '\''admin'\'', '\''email_verified_at'\'' => now()]); echo '\''✅ Usuário admin criado!\nEmail: admin@admin.com\nSenha: admin123'\'';"'
echo ""

echo "📋 DADOS DE LOGIN:"
echo "📧 Email: admin@admin.com"
echo "🔐 Senha: admin123"
echo "👤 Tipo: Administrador (todas as permissões)"
echo ""

echo "🌐 Para ver a URL da aplicação:"
echo "railway domain"
