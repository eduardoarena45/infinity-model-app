<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('locais', function (Blueprint $table) {
            $table->id();
            $table->string('estado', 2); // Ex: SP, RJ
            $table->string('cidade');    // Ex: Franca, Ribeirão Preto
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('locais'); }
};