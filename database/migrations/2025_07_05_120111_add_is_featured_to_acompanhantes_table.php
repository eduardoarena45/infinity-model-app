<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acompanhantes', function (Blueprint $table) {
            // Adiciona a coluna para o destaque. Por defeito, ninguém é destaque.
            $table->boolean('is_featured')->default(false)->after('is_verified');
        });
    }

    public function down(): void
    {
        Schema::table('acompanhantes', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });
    }
};