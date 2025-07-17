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
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->date('data_despesa');
            $table->enum('tipo', ['fixa', 'variavel']);
            $table->string('categoria'); // Ex: funcionarios, agua, energia, produtos, manutencao, marketing, etc
            $table->text('observacoes')->nullable();
            $table->string('comprovante')->nullable(); // Caminho para arquivo de comprovante
            $table->boolean('recorrente')->default(false); // Para despesas fixas que se repetem
            $table->integer('dia_vencimento')->nullable(); // Para despesas recorrentes
            $table->enum('status', ['pendente', 'paga', 'vencida'])->default('paga');
            $table->string('forma_pagamento')->nullable(); // dinheiro, cartao, pix, etc
            $table->unsignedBigInteger('user_id'); // Usuário que cadastrou
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['data_despesa', 'tipo', 'categoria']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};
