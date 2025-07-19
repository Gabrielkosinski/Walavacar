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
            // Campos para controle de finalização
            $table->timestamp('data_conclusao')->nullable()->after('data_entrada_fila');
            $table->unsignedBigInteger('concluido_por')->nullable()->after('data_conclusao');
            
            // Campos para controle de pagamento
            $table->decimal('valor_original', 10, 2)->nullable()->after('valor');
            $table->decimal('valor_desconto', 10, 2)->default(0)->after('valor_original');
            $table->decimal('valor_final', 10, 2)->nullable()->after('valor_desconto');
            $table->decimal('valor_pago', 10, 2)->nullable()->after('valor_final');
            $table->timestamp('data_pagamento')->nullable()->after('valor_pago');
            $table->unsignedBigInteger('recebido_por')->nullable()->after('data_pagamento');
            $table->text('observacoes_pagamento')->nullable()->after('recebido_por');
            
            // Foreign keys
            $table->foreign('concluido_por')->references('id')->on('users')->onDelete('set null');
            $table->foreign('recebido_por')->references('id')->on('users')->onDelete('set null');
            
            // Índices para relatórios
            $table->index(['status', 'data_pagamento']);
            $table->index(['forma_pagamento', 'data_pagamento']);
            $table->index(['data_conclusao']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            // Remover foreign keys primeiro
            $table->dropForeign(['concluido_por']);
            $table->dropForeign(['recebido_por']);
            
            // Remover índices
            $table->dropIndex(['status', 'data_pagamento']);
            $table->dropIndex(['forma_pagamento', 'data_pagamento']);
            $table->dropIndex(['data_conclusao']);
            
            // Remover colunas
            $table->dropColumn([
                'data_conclusao',
                'concluido_por',
                'valor_original',
                'valor_desconto',
                'valor_final',
                'valor_pago',
                'data_pagamento',
                'recebido_por',
                'observacoes_pagamento'
            ]);
        });
    }
};
