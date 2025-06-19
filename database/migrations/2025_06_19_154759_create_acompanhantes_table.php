<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acompanhantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nome_artistico')->nullable();
            $table->string('imagem_principal_url')->nullable()->default('https://placehold.co/400x600/ccc/333?text=Perfil');
            $table->date('data_nascimento')->nullable();
            $table->text('descricao_curta')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->decimal('valor_hora', 8, 2)->nullable();
            $table->string('whatsapp')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acompanhantes');
    }
};