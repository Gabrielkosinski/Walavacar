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
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('whatsapp', 15)->nullable()->after('telefone');
        });

        Schema::table('atendimentos', function (Blueprint $table) {
            $table->enum('forma_pagamento', ['dinheiro', 'pix', 'credito', 'debito'])->nullable()->after('valor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('whatsapp');
        });

        Schema::table('atendimentos', function (Blueprint $table) {
            $table->dropColumn('forma_pagamento');
        });
    }
};
