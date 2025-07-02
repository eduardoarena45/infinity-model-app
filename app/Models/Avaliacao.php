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
     * Isto corrige o erro, forçando o Laravel a usar 'avaliacoes'.
     * @var string
     */
    protected $table = 'avaliacoes';

    protected $fillable = [
        'acompanhante_id',
        'nome_autor',
        'nota',
        'comentario',
    ];

    public function acompanhante(): BelongsTo
    {
        return $this->belongsTo(Acompanhante::class);
    }
}