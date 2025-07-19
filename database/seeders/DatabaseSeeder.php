<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuário de teste
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@lavacar.com',
        ]);

        // Executar seeders na ordem correta
        $this->call([
            FiliaisSeeder::class,
            ClientesSeeder::class,
            CarrosSeeder::class,
            ServicosSeeder::class,
            AtendimentosSeeder::class,
        ]);
    }
}
