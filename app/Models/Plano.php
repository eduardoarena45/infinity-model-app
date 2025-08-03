<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'preco',
        'limite_fotos',
        'limite_videos',
        'destaque',
        'permite_videos',
        'limite_descricao',
        'prioridade', // <-- Adicionado
    ];
}
