<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // CORREÇÃO: O nome da tabela agora é 'midias' (plural)
        Schema::create('midias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('path');
            $table->string('type')->default('image');
            $table->string('status')->default('pendente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // CORREÇÃO: O nome da tabela agora é 'midias' (plural)
        Schema::dropIfExists('midias');
    }
};