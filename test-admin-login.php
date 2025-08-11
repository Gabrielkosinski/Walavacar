<?php
// Script para testar se o usuário admin foi criado
require_once 'vendor/autoload.php';

// Simulação de teste de credenciais
$testCredentials = [
    'email' => 'admin@admin.com',
    'password' => 'admin123'
];

echo "🔍 TESTE DE CREDENCIAIS ADMIN\n";
echo "===============================\n";
echo "📧 Email: " . $testCredentials['email'] . "\n";
echo "🔑 Senha: " . $testCredentials['password'] . "\n";
echo "\n";

echo "🌐 URLs para testar:\n";
echo "- Login: https://walavacar-production.up.railway.app/login\n";
echo "- Dashboard: https://walavacar-production.up.railway.app/dashboard\n";
echo "- Home: https://walavacar-production.up.railway.app/\n";
echo "\n";

echo "📊 Status esperado após login:\n";
echo "✅ Redirecionamento para dashboard\n";
echo "✅ Menu de administração visível\n";
echo "✅ Acesso a todas as funcionalidades\n";
echo "\n";

echo "🔧 Se ainda não funcionar, execute no Railway:\n";
echo "php artisan migrate --force\n";
echo "php artisan db:seed --class=CreateAdminUserSeeder\n";
echo "php artisan optimize:clear\n";
