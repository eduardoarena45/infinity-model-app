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
        // Adicionamos esta verificação: Só tenta criar a tabela se ela ainda não existir.
        if (!Schema::hasTable('planos')) {
            Schema::create('planos', function (Blueprint $table) {
                $table->id();
                $table->string('nome'); // Ex: "Plano Básico", "Plano Premium"
                $table->string('slug')->unique(); // Ex: "basico", "premium" (para usar em links)
                $table->text('descricao'); // Uma breve descrição do plano
                $table->decimal('preco', 8, 2); // Preço do plano, ex: 29.90
                $table->integer('limite_fotos'); // Número de fotos permitidas
                $table->boolean('permite_videos')->default(false); // Se permite vídeos na galeria
                $table->boolean('destaque')->default(false); // Se o plano dá o status de 'is_featured'
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planos');
    }
};
