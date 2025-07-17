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
            // Campos para cancelamento
            $table->string('motivo_cancelamento')->nullable()->after('observacoes_pagamento');
            $table->timestamp('data_cancelamento')->nullable()->after('motivo_cancelamento');
            $table->unsignedBigInteger('cancelado_por')->nullable()->after('data_cancelamento');
            
            // Campo pago se não existir
            if (!Schema::hasColumn('atendimentos', 'pago')) {
                $table->boolean('pago')->default(false)->after('forma_pagamento');
            }
            
            // Foreign key para cancelado_por
            $table->foreign('cancelado_por')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            // Remover foreign key primeiro
            $table->dropForeign(['cancelado_por']);
            
            // Remover campos
            $table->dropColumn([
                'motivo_cancelamento',
                'data_cancelamento', 
                'cancelado_por'
            ]);
            
            // Remover campo pago se foi adicionado
            if (Schema::hasColumn('atendimentos', 'pago')) {
                $table->dropColumn('pago');
            }
        });
    }
};
