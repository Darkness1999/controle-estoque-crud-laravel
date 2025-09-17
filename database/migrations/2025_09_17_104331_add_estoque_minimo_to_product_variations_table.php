<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variations', function (Blueprint $table) {
            // Adiciona a nova coluna após a coluna 'estoque_atual'
            $table->integer('estoque_minimo')->default(0)->after('estoque_atual');
        });
    }

    public function down(): void
    {
        Schema::table('product_variations', function (Blueprint $table) {
            // Remove a coluna caso a migration seja revertida
            $table->dropColumn('estoque_minimo');
        });
    }

};
