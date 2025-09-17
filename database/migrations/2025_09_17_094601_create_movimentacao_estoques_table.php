<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimentacao_estoques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variation_id')->constrained('product_variations');
            $table->foreignId('user_id')->constrained('users'); // Para saber QUEM fez a movimentação
            $table->enum('tipo', ['entrada', 'saida']); // O tipo de movimento
            $table->integer('quantidade'); // A quantidade de itens movimentados
            $table->string('motivo')->nullable(); // Ex: 'Venda Cliente', 'Compra Fornecedor', 'Avaria'
            $table->text('observacao')->nullable(); // Um campo para notas adicionais
            $table->timestamps(); // Registra QUANDO a movimentação ocorreu
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacao_estoques');
    }
};
