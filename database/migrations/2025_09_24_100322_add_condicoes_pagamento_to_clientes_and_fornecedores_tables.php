<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('condicoes_pagamento')->nullable()->after('endereco');
        });

        Schema::table('fornecedores', function (Blueprint $table) {
            $table->string('condicoes_pagamento')->nullable()->after('endereco');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('condicoes_pagamento');
        });

        Schema::table('fornecedores', function (Blueprint $table) {
            $table->dropColumn('condicoes_pagamento');
        });
    }
};