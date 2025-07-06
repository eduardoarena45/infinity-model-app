<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acompanhantes', function (Blueprint $table) {
            // Adiciona a coluna de status. Por defeito, um novo perfil fica 'pendente'.
            // Outros status podem ser 'aprovado', 'rejeitado'.
            $table->string('status')->default('pendente')->after('is_featured');
        });
    }

    public function down(): void
    {
        Schema::table('acompanhantes', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};