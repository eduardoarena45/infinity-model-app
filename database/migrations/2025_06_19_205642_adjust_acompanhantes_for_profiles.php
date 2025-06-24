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
    Schema::table('acompanhantes', function (Blueprint $table) {
        // Adiciona a coluna para o vínculo com o usuário.
        // constrained() cria a chave estrangeira e onDelete('cascade') apaga o perfil se a usuária for deletada.
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // Remove a coluna 'idade' que não usaremos mais.
        $table->dropColumn('idade');

        // Adiciona a coluna para a data de nascimento.
        $table->date('data_nascimento')->nullable()->after('imagem_principal_url');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acompanhantes', function (Blueprint $table) {
            //
        });
    }
};
