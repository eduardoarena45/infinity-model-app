<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acompanhantes', function (Blueprint $table) {
            // Adiciona a coluna para o selo de verificação.
            $table->boolean('is_verified')->default(false)->after('whatsapp');
        });
    }

    public function down(): void
    {
        Schema::table('acompanhantes', function (Blueprint $table) {
            $table->dropColumn('is_verified');
        });
    }
};