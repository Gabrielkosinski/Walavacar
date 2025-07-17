#!/bin/bash

# 🚀 SCRIPT DE DEPLOY AUTOMATIZADO - WALAVACAR
echo "🚀 Iniciando deploy do WaLavacar..."

# 1. Otimizações para produção
echo "📦 Otimizando para produção..."
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Migrações de banco
echo "🗄️ Executando migrações..."
php artisan migrate --force

# 3. Verificar se há usuário admin
echo "👤 Verificando usuário admin..."
if ! php artisan tinker --execute="echo App\Models\User::count();" | grep -q "1"; then
    echo "🔧 Criando usuário admin padrão..."
    php artisan tinker --execute="
        \$user = App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@walavacar.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now()
        ]);
        echo 'Usuário criado: ' . \$user->email;
    "
fi

# 4. Verificação de segurança
echo "🔒 Verificação final de segurança..."
if [ -f "check-security.sh" ]; then
    ./check-security.sh
fi

echo "✅ Deploy concluído com sucesso!"
echo "📧 Login: admin@walavacar.com"
echo "🔑 Senha: admin123"
echo "⚠️ ALTERE A SENHA após o primeiro login!"
