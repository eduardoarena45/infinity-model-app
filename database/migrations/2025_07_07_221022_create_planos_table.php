<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('planos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique(); // Ex: Básico, Premium
            $table->decimal('preco', 8, 2)->default(0);
            $table->integer('limite_fotos')->default(5);
            $table->integer('limite_videos')->default(0);
            $table->boolean('permite_destaque')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('planos'); }
};