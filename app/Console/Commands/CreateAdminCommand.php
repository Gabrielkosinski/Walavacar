<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CreateAdminCommand extends Command
{
    /**
     * Nome e assinatura do comando
     */
    protected $signature = 'admin:create {--force : Force creation even if admin exists}';

    /**
     * Descrição do comando
     */
    protected $description = 'Create admin user with multiple fallback strategies';

    /**
     * Executar comando
     */
    public function handle()
    {
        $this->info('🔥 CRIANDO USUÁRIO ADMIN - ESTRATÉGIAS MÚLTIPLAS');
        $this->info('================================================');

        // Verificar se admin já existe
        if (!$this->option('force')) {
            $existingAdmin = DB::table('users')->where('email', 'admin@admin.com')->first();
            if ($existingAdmin) {
                $this->info('✅ Admin já existe! Email: admin@admin.com');
                $this->info('🔑 Senha: admin123');
                return 0;
            }
        }

        $adminData = [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'tipo' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Estratégia 1: Eloquent Model
        $this->info('🔥 TENTATIVA 1: Eloquent Model...');
        try {
            if ($this->option('force')) {
                User::where('email', 'admin@admin.com')->delete();
            }
            
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'tipo' => 'admin',
                'email_verified_at' => now(),
            ]);
            
            $this->info('✅ SUCESSO: Admin criado via Eloquent Model');
            $this->info('📧 Email: admin@admin.com');
            $this->info('🔑 Senha: admin123');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Falha Eloquent: ' . $e->getMessage());
        }

        // Estratégia 2: Query Builder - Delete + Insert
        $this->info('🔥 TENTATIVA 2: Query Builder Delete+Insert...');
        try {
            DB::table('users')->where('email', 'admin@admin.com')->delete();
            DB::table('users')->insert($adminData);
            
            $this->info('✅ SUCESSO: Admin criado via Query Builder');
            $this->info('📧 Email: admin@admin.com');
            $this->info('🔑 Senha: admin123');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Falha Query Builder: ' . $e->getMessage());
        }

        // Estratégia 3: Insert Or Ignore
        $this->info('🔥 TENTATIVA 3: Insert Or Ignore...');
        try {
            DB::table('users')->insertOrIgnore($adminData);
            
            $this->info('✅ SUCESSO: Admin criado via InsertOrIgnore');
            $this->info('📧 Email: admin@admin.com');
            $this->info('🔑 Senha: admin123');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Falha InsertOrIgnore: ' . $e->getMessage());
        }

        // Estratégia 4: SQL Raw
        $this->info('🔥 TENTATIVA 4: SQL Raw...');
        try {
            DB::statement("
                INSERT INTO users (name, email, password, tipo, email_verified_at, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?)
                ON CONFLICT (email) DO UPDATE SET
                    name = EXCLUDED.name,
                    password = EXCLUDED.password,
                    tipo = EXCLUDED.tipo,
                    updated_at = EXCLUDED.updated_at
            ", [
                $adminData['name'],
                $adminData['email'],
                $adminData['password'],
                $adminData['tipo'],
                $adminData['email_verified_at'],
                $adminData['created_at'],
                $adminData['updated_at'],
            ]);
            
            $this->info('✅ SUCESSO: Admin criado via SQL Raw');
            $this->info('📧 Email: admin@admin.com');
            $this->info('🔑 Senha: admin123');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Falha SQL Raw: ' . $e->getMessage());
        }

        $this->error('🚨 ERRO CRÍTICO: Todas as 4 estratégias falharam!');
        $this->error('🔍 Verifique se a tabela users existe e tem as colunas corretas');
        return 1;
    }
}
