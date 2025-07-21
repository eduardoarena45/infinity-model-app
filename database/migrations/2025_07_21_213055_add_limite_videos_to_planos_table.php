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
        Schema::table('planos', function (Blueprint $table) {
            // Adiciona a nova coluna 'limite_videos' depois da coluna 'limite_fotos'
            $table->integer('limite_videos')->unsigned()->default(0)->after('limite_fotos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planos', function (Blueprint $table) {
            // Remove a coluna caso seja necessário reverter a migration
            $table->dropColumn('limite_videos');
        });
    }
};