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
            // 1. Altera a coluna para ser do tipo DATETIME, que não tem o limite de 2038.
            // 2. Permite que a coluna aceite valores nulos (para planos sem expiração).
            // 3. O método change() aplica a alteração.
            $table->dateTime('data_fim')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assinaturas', function (Blueprint $table) {
            // Este método reverte a alteração caso seja necessário.
            // ATENÇÃO: pode haver perda de dados se existirem datas posteriores a 2038.
            $table->timestamp('data_fim')->nullable(false)->change();
        });
    }
};
