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
            // Adicionar novos status para controle de fila
            $table->dropColumn('status');
        });
        
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->enum('status', ['aguardando', 'em_fila', 'em_andamento', 'concluido', 'cancelado'])->default('aguardando')->after('filial_id');
            $table->integer('posicao_fila')->nullable()->after('status');
            $table->timestamp('data_entrada_fila')->nullable()->after('posicao_fila');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->dropColumn(['posicao_fila', 'data_entrada_fila']);
            $table->dropColumn('status');
        });
        
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->enum('status', ['agendado', 'em_andamento', 'concluido', 'cancelado'])->default('agendado')->after('filial_id');
        });
    }
};
