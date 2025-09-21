<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lote_estoques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variation_id')->constrained('product_variations')->cascadeOnDelete();
            $table->string('lote')->nullable();
            $table->date('data_validade')->nullable();
            $table->integer('quantidade_atual');
            $table->timestamps(); // Registra a data de entrada do lote
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lote_estoques');
    }
};