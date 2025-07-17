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
        Schema::create('acompanhantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // --- DADOS DO PERFIL ---
            $table->string('nome_artistico')->nullable();
            $table->string('foto_principal_path')->nullable(); // Coluna para o caminho da foto
            $table->date('data_nascimento')->nullable();
            $table->text('descricao')->nullable(); // Nome corrigido para 'descricao'
            $table->string('whatsapp')->nullable();
            $table->decimal('valor_hora', 8, 2)->nullable();
            
            // --- LOCALIZAÇÃO ---
            $table->foreignId('cidade_id')->nullable()->constrained('cidades')->onDelete('set null');

            // --- STATUS E CONTROLE DO ADMIN ---
            $table->string('status')->default('pendente'); // pendente, aprovado, rejeitado
            $table->boolean('is_verified')->default(false); // Verificado
            $table->boolean('is_featured')->default(false); // Destaque
            
            $table->timestamps();
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
