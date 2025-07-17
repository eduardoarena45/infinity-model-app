<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Renomeei a tabela para 'media' (singular) para seguir a convenção do Laravel
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            // Coluna renomeada para 'user_id'
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Coluna renomeada para 'path'
            $table->string('path');
            $table->string('type')->default('image');
            // ADICIONADA a coluna 'status', que era a mais importante
            $table->string('status')->default('pendente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};