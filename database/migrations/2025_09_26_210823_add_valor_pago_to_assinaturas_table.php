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
            // =======================================================
            // =================== INÍCIO DA ALTERAÇÃO ==================
            // =======================================================

            // Adicionamos a nossa nova coluna para "congelar" o preço no momento da transação.
            // 'decimal' é o tipo de dados correto para valores monetários para evitar erros de arredondamento.
            // (10, 2) significa que podemos guardar valores até 99.999.999,99.
            // 'nullable' permite que o valor seja nulo (útil para planos grátis ou assinaturas antigas).
            // 'after' posiciona a nova coluna a seguir à coluna 'plano_id' para uma melhor organização.
            $table->decimal('valor_pago', 10, 2)->nullable()->after('plano_id');

            // =======================================================
            // ==================== FIM DA ALTERAÇÃO =====================
            // =======================================================
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assinaturas', function (Blueprint $table) {
            // =======================================================
            // =================== INÍCIO DA ALTERAÇÃO ==================
            // =======================================================

            // A função 'down' deve reverter a 'up'. Se a migração for desfeita,
            // esta linha remove a coluna que adicionámos.
            $table->dropColumn('valor_pago');

            // =======================================================
            // ==================== FIM DA ALTERAÇÃO =====================
            // =======================================================
        });
    }
};
