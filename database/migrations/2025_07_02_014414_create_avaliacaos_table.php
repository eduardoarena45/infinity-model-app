<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acompanhante_id')->constrained('acompanhantes')->onDelete('cascade');
            
            // CORREÇÃO: Nome da coluna padronizado para 'nome_avaliador'
            $table->string('nome_avaliador'); 
            
            // O comentário da avaliação (agora é obrigatório)
            $table->text('comentario'); 
            
            // A nota da avaliação (de 1 a 5)
            $table->unsignedTinyInteger('nota');
            
            // ADIÇÃO: Coluna para o status da moderação
            $table->string('status')->default('pendente');
            
            // ADIÇÃO: Coluna para o IP do usuário
            $table->ipAddress('ip_address')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};