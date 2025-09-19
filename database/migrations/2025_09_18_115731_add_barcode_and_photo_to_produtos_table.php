<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->string('codigo_barras')->nullable()->unique()->after('descricao');
            $table->string('foto_path')->nullable()->after('codigo_barras');
        });
    }

    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn(['codigo_barras', 'foto_path']);
        });
    }
};
