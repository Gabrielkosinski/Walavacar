<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            // Índices para melhorar performance do dashboard
            $table->index(['status', 'created_at'], 'idx_status_created_at');
            $table->index(['cliente_id', 'status'], 'idx_cliente_status');
            $table->index(['carro_id', 'status'], 'idx_carro_status');
            $table->index('created_at', 'idx_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->dropIndex('idx_status_created_at');
            $table->dropIndex('idx_cliente_status');
            $table->dropIndex('idx_carro_status');
            $table->dropIndex('idx_created_at');
        });
    }
};
