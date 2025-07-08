<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada com o modelo.
     * Isto corrige o erro, forçando o Laravel a usar 'locais'.
     * @var string
     */
    protected $table = 'locais';

    protected $fillable = ['estado', 'cidade'];
}
