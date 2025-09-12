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
        // Cria a tabela de Estados (VAZIA)
        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('uf', 2);
        });

        // Cria a tabela de Cidades (VAZIA)
        Schema::create('cidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estado_id')->constrained('estados')->onDelete('cascade');
            $table->string('nome');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cidades');
        Schema::dropIfExists('estados');
    }
};
