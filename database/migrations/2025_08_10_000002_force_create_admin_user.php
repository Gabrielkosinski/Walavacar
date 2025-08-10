<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Força criação do usuário admin
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin WaLavacar',
                'email' => 'admin@admin.com', 
                'password' => Hash::make('admin123'),
                'tipo' => 'admin',
                'email_verified_at' => now(),
            ]
        );
        
        echo "✅ Usuário admin criado/verificado!\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::where('email', 'admin@admin.com')->delete();
    }
};
