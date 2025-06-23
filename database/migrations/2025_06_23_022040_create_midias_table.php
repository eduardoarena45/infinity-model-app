<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('midias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acompanhante_id')->constrained()->onDelete('cascade');
            $table->string('caminho_arquivo');
            $table->string('tipo')->default('foto');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('midias');
    }
};