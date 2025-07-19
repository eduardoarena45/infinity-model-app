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
        Schema::table('assinaturas', function (Blueprint $table) {
            // Adiciona as colunas que estão faltando, logo após a coluna 'plano_id'
            $table->string('status')->default('aguardando_pagamento')->after('plano_id');
            $table->timestamp('data_inicio')->nullable()->after('status');

            // Renomeia a coluna antiga para o nome correto que o código espera
            $table->renameColumn('data_expiracao', 'data_fim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assinaturas', function (Blueprint $table) {
            // Este método desfaz o que o método up() fez, caso você precise reverter.
            $table->renameColumn('data_fim', 'data_expiracao');
            $table->dropColumn(['status', 'data_inicio']);
        });
    }
};
