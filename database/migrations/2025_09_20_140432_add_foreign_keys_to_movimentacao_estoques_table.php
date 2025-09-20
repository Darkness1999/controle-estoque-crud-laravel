<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movimentacao_estoques', function (Blueprint $table) {
            // Adiciona a chave estrangeira para fornecedor, que pode ser nula
            $table->foreignId('fornecedor_id')->nullable()->constrained('fornecedores')->after('user_id');
            // Adiciona a chave estrangeira para cliente, que pode ser nula
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->after('fornecedor_id');
        });
    }

    public function down(): void
    {
        Schema::table('movimentacao_estoques', function (Blueprint $table) {
            $table->dropForeign(['fornecedor_id']);
            $table->dropForeign(['cliente_id']);
            $table->dropColumn(['fornecedor_id', 'cliente_id']);
        });
    }
};