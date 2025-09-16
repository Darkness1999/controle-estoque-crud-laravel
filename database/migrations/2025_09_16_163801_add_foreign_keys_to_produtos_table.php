<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up(): void
{
    Schema::table('produtos', function (Blueprint $table) {
        // Adiciona as restrições de chave estrangeira
        $table->foreign('categoria_id')->references('id')->on('categorias');
        $table->foreign('marca_id')->references('id')->on('marcas');
        $table->foreign('fornecedor_id')->references('id')->on('fornecedors');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            //
        });
    }
};
