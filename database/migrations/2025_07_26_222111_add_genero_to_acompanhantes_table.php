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
        Schema::table('acompanhantes', function (Blueprint $table) {
        // Adiciona a coluna para o gênero, pode ser nula
        $table->string('genero')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acompanhantes', function (Blueprint $table) {
            //
        });
    }
};
