<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin se não existir
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

        echo "✅ Usuário admin criado/verificado com sucesso!\n";
        echo "📧 Email: admin@admin.com\n";
        echo "🔐 Senha: admin123\n";
        echo "👤 Tipo: admin\n";
    }
}
