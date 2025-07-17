<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estado extends Model
{
    use HasFactory;

    protected $table = 'estados';

    // ESTA LINHA É A SOLUÇÃO: Diz ao Laravel para não usar as colunas de data e hora.
    public $timestamps = false;

    protected $fillable = ['id', 'nome', 'uf'];

    public function cidades(): HasMany
    {
        return $this->hasMany(Cidade::class);
    }
}
