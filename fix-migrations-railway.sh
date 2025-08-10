#!/bin/bash

# 🔧 Fix Migration - Railway PostgreSQL
# Resolve problema de migration duplicada

echo "🔧 Corrigindo migrations no Railway..."

# 1. Verificar se a tabela users já existe
echo "📋 Verificando tabelas existentes..."
railway run php artisan tinker --execute="
use Illuminate\Support\Facades\Schema;
echo 'Tabelas existentes:';
\$tables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = \'public\'');
foreach(\$tables as \$table) {
    echo \$table->table_name . PHP_EOL;
}
"

# 2. Marcar migration como executada se a tabela já existe
echo "🗄️ Marcando migrations como executadas..."
railway run php artisan tinker --execute="
use Illuminate\Support\Facades\Schema;
if(Schema::hasTable('users')) {
    echo 'Tabela users já existe, marcando migration como executada...';
    DB::table('migrations')->insertOrIgnore([
        'migration' => '2025_07_01_000000_create_users_table',
        'batch' => 1
    ]);
    echo 'Migration marcada como executada.';
} else {
    echo 'Tabela users não existe, migration pode ser executada normalmente.';
}
"

# 3. Executar apenas migrations pendentes
echo "⚡ Executando migrations pendentes..."
railway run php artisan migrate --force

echo "✅ Migrations corrigidas!"

# 4. Verificar status
echo "📊 Status das migrations:"
railway run php artisan migrate:status

echo "🎉 Pronto! Sistema online."
