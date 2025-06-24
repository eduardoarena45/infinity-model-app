<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Servico extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    /**
     * RELAÇÃO: Um Serviço pertence a Muitas Acompanhantes.
     */
    public function acompanhantes(): BelongsToMany
    {
        return $this->belongsToMany(Acompanhante::class, 'acompanhante_servico');
    }
}