<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Midia extends Model
{
    use HasFactory;
    protected $fillable = ['acompanhante_id', 'caminho_arquivo', 'tipo']; // Adicione 'tipo' aqui
    public function acompanhante(): BelongsTo { return $this->belongsTo(Acompanhante::class); }
}