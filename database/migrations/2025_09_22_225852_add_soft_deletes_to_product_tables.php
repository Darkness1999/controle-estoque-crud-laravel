<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->softDeletes(); // Adiciona a coluna nullable 'deleted_at'
        });

        Schema::table('product_variations', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Remove a coluna 'deleted_at'
        });

        Schema::table('product_variations', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};