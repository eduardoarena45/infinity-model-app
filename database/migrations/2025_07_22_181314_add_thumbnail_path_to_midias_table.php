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
        Schema::table('midias', function (Blueprint $table) {
            // Adiciona a nova coluna para guardar o caminho da thumbnail
            $table->string('thumbnail_path')->nullable()->after('path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('midias', function (Blueprint $table) {
            // Remove a coluna caso a migration seja revertida
            $table->dropColumn('thumbnail_path');
        });
    }
};