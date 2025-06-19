<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // No arquivo database/migrations/xxxx_xx_xx_xxxxxx_create_acompanhantes_table.php
public function up(): void
{
    Schema::create('acompanhantes', function (Blueprint $table) {
        $table->id(); // ID único para cada perfil
        $table->string('nome_artistico');
        $table->string('imagem_principal_url'); // URL da foto de capa
        $table->integer('idade');
        $table->string('cidade');
        $table->string('estado', 2); // Ex: SP, RJ
        $table->text('descricao_curta');
        $table->decimal('valor_hora', 8, 2); // Preço, ex: 350.00
        $table->string('whatsapp');
        $table->timestamps(); // Cria as colunas created_at e updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acompanhantes');
    }
};
