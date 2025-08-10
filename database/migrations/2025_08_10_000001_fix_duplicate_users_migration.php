<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Esta migration serve apenas para marcar a migration problemática como executada
        // Não faz nada se a tabela users já existir
        
        if (Schema::hasTable('users')) {
            // Marcar a migration problemática como executada
            DB::table('migrations')->insertOrIgnore([
                'migration' => '2025_07_24_232725_create_users_table',
                'batch' => 1
            ]);
            
            echo "✅ Migration problemática marcada como executada\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove da tabela de migrations se necessário
        DB::table('migrations')->where('migration', '2025_07_24_232725_create_users_table')->delete();
    }
};
