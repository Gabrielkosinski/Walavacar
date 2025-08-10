#!/bin/bash

# 🚀 SOLUÇÃO RÁPIDA - Migration Duplicada Railway
echo "🔧 Resolvendo migration duplicada no Railway..."

# Opção 1: Ignorar erro e continuar
echo "📋 COMANDO PARA RESOLVER:"
echo ""
echo "# Execute este comando no Railway:"
echo 'railway run php artisan tinker --execute="DB::table('\''migrations'\'')->insertOrIgnore(['\''migration'\'' => '\''2025_07_01_000000_create_users_table'\'', '\''batch'\'' => 1]); echo '\''Migration marcada como executada'\'';"'
echo ""
echo "# Depois execute:"
echo "railway run php artisan migrate --force"
echo ""

# Opção 2: Reset completo (se necessário)
echo "🔄 OU, se ainda der erro, RESET COMPLETO:"
echo "railway run php artisan migrate:fresh --force"
echo "railway run php artisan db:seed --force"
echo ""

# Opção 3: Criar usuário admin novamente
echo "👤 Criar usuário admin após reset:"
echo 'railway run php artisan tinker --execute="App\Models\User::create(['\''name'\'' => '\''Admin'\'', '\''email'\'' => '\''admin@admin.com'\'', '\''password'\'' => bcrypt('\''admin123'\''), '\''tipo'\'' => '\''admin'\'']); echo '\''Usuário admin criado!'\'';"'

echo ""
echo "✅ Escolha uma opção acima e execute!"
