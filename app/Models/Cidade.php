<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Importa a classe HasMany

class Cidade extends Model
{
    use HasFactory;

    protected $table = 'cidades';
    public $timestamps = false;
    protected $fillable = ['estado_id', 'nome'];

    /**
     * Uma Cidade pertence a um Estado.
     */
    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    /**
     * Uma Cidade tem muitas Acompanhantes.
     * ESTA É A RELAÇÃO QUE FALTAVA.
     */
    public function acompanhantes(): HasMany
    {
        return $this->hasMany(Acompanhante::class);
    }
}
