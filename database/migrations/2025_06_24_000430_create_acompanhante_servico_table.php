<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela que conecta uma acompanhante a um serviço
        Schema::create('acompanhante_servico', function (Blueprint $table) {
            $table->primary(['acompanhante_id', 'servico_id']); // Chave primária composta
            $table->foreignId('acompanhante_id')->constrained()->onDelete('cascade');
            $table->foreignId('servico_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acompanhante_servico');
    }
};