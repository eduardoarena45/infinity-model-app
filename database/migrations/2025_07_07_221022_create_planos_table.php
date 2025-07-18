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
        Schema::create('planos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique(); // Coluna que estava faltando
            $table->text('descricao'); // Coluna que estava faltando
            $table->decimal('preco', 8, 2);
            $table->integer('limite_fotos');
            $table->boolean('permite_videos')->default(false); // Nome e tipo corrigidos
            $table->boolean('destaque')->default(false); // Nome e tipo corrigidos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planos');
    }
};
