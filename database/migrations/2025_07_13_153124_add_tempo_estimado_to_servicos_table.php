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
        Schema::table('servicos', function (Blueprint $table) {
            // ⏱️ Tempo estimado para cada serviço (em minutos)
            $table->integer('tempo_estimado_minutos')->default(30)->after('preco');
            $table->text('descricao_tecnica')->nullable()->after('tempo_estimado_minutos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicos', function (Blueprint $table) {
            $table->dropColumn(['tempo_estimado_minutos', 'descricao_tecnica']);
        });
    }
};
