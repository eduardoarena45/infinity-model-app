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
            $table->decimal('valor_15_min', 8, 2)->nullable()->after('valor_hora');
            $table->decimal('valor_30_min', 8, 2)->nullable()->after('valor_15_min');
            $table->decimal('valor_pernoite', 8, 2)->nullable()->after('valor_30_min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acompanhantes', function (Blueprint $table) {
            $table->dropColumn(['valor_15_min', 'valor_30_min', 'valor_pernoite']);
        });
    }
};
