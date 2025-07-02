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
            // Liga a avaliação a um perfil de acompanhante
            $table->foreignId('acompanhante_id')->constrained()->onDelete('cascade');
            // O nome de quem deixou a avaliação
            $table->string('nome_autor');
            // A nota da avaliação (de 1 a 5)
            $table->unsignedTinyInteger('nota');
            // O comentário da avaliação (pode ser nulo)
            $table->text('comentario')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};