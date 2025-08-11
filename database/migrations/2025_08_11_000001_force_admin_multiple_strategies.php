<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Executar migração - FORCE CREATE ADMIN com múltiplas tentativas
     */
    public function up(): void
    {
        // 🚨 FORÇA CRIAÇÃO DO USUÁRIO ADMIN - MÚLTIPLAS TENTATIVAS
        $this->createAdminUser();
    }

    /**
     * Reverter migração
     */
    public function down(): void
    {
        // Remove admin user
        DB::table('users')->where('email', 'admin@admin.com')->delete();
    }

    /**
     * 🔥 FORÇA CRIAÇÃO DO ADMIN COM MÚLTIPLAS ESTRATÉGIAS
     */
    private function createAdminUser(): void
    {
        $adminData = [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'tipo' => 'admin',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        echo "🔥 TENTATIVA 1: Deletar e recriar admin...\n";
        // Estratégia 1: Delete + Insert
        try {
            DB::table('users')->where('email', 'admin@admin.com')->delete();
            DB::table('users')->insert($adminData);
            echo "✅ SUCESSO: Admin criado via delete+insert\n";
            return;
        } catch (\Exception $e) {
            echo "❌ Falha tentativa 1: " . $e->getMessage() . "\n";
        }

        echo "🔥 TENTATIVA 2: Insert or ignore...\n";
        // Estratégia 2: Insert or Ignore
        try {
            DB::table('users')->insertOrIgnore($adminData);
            echo "✅ SUCESSO: Admin criado via insertOrIgnore\n";
            return;
        } catch (\Exception $e) {
            echo "❌ Falha tentativa 2: " . $e->getMessage() . "\n";
        }

        echo "🔥 TENTATIVA 3: Upsert (update or insert)...\n";
        // Estratégia 3: Upsert
        try {
            DB::table('users')->upsert(
                [$adminData],
                ['email'], // unique key
                ['name', 'password', 'tipo', 'updated_at'] // update these if exists
            );
            echo "✅ SUCESSO: Admin criado via upsert\n";
            return;
        } catch (\Exception $e) {
            echo "❌ Falha tentativa 3: " . $e->getMessage() . "\n";
        }

        echo "🔥 TENTATIVA 4: Raw SQL direto...\n";
        // Estratégia 4: SQL direto
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
            echo "✅ SUCESSO: Admin criado via SQL direto\n";
            return;
        } catch (\Exception $e) {
            echo "❌ Falha tentativa 4: " . $e->getMessage() . "\n";
        }

        echo "🚨 ERRO CRÍTICO: Não foi possível criar usuário admin!\n";
        throw new \Exception("FALHA CRÍTICA: Usuário admin não pôde ser criado após 4 tentativas!");
    }
};
