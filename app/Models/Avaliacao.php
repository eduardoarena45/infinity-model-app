<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avaliacao extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada com o modelo.
     */
    protected $table = 'avaliacoes';

    /**
     * Os atributos que podem ser atribuídos em massa.
     */
    protected $fillable = [
        'acompanhante_id',
        'nome_avaliador', // <-- CORRIGIDO
        'nota',
        'comentario',
        'status',         // <-- ADICIONADO
        'ip_address',     // <-- ADICIONADO
    ];

    /**
     * Define o relacionamento com a Acompanhante.
     */
    public function acompanhante(): BelongsTo
    {
        return $this->belongsTo(Acompanhante::class);
    }
}