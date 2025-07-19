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
            // ⏱️ Campos para cronometragem do serviço
            $table->timestamp('inicio_servico')->nullable()->after('status');
            $table->timestamp('fim_servico')->nullable()->after('inicio_servico');
            $table->integer('tempo_execucao_minutos')->nullable()->after('fim_servico');
            
            // 👤 Campo opcional para funcionário responsável
            $table->string('funcionario_responsavel')->nullable()->after('tempo_execucao_minutos');
            
            // 📋 Campos adicionais para métricas
            $table->text('observacoes_tecnicas')->nullable()->after('funcionario_responsavel');
            $table->decimal('nota_qualidade', 3, 2)->nullable()->after('observacoes_tecnicas'); // 0.00 a 10.00
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->dropColumn([
                'inicio_servico',
                'fim_servico', 
                'tempo_execucao_minutos',
                'funcionario_responsavel',
                'observacoes_tecnicas',
                'nota_qualidade'
            ]);
        });
    }
};
