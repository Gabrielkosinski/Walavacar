<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Inserir diretamente na tabela users
        DB::table('users')->insertOrIgnore([
            'name' => 'Admin WaLavacar',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'tipo' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ Usuário admin inserido diretamente!\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', 'admin@admin.com')->delete();
    }
};
