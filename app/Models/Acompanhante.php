<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Acompanhante extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','nome_artistico','imagem_principal_url','data_nascimento','cidade','estado','descricao_curta','valor_hora','whatsapp','is_verified',];
    protected $casts = ['imagem_principal_url' => 'string', 'is_verified' => 'boolean'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function midias(): HasMany { return $this->hasMany(Midia::class); }
    public function servicos(): BelongsToMany { return $this->belongsToMany(Servico::class, 'acompanhante_servico'); }

    /**
     * NOVA RELAÇÃO: Uma Acompanhante tem muitas Avaliações.
     */
    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class);
    }

    /**
     * ATRIBUTO MÁGICO: Calcula a média das notas das avaliações.
     */
    public function getNotaMediaAttribute(): float
    {
        // avg() é uma função do Laravel que calcula a média de uma coluna
        return round($this->avaliacoes()->avg('nota'), 1);
    }

    public function getIdadeAttribute(): ?int
    {
        if ($this->data_nascimento) { return Carbon::parse($this->data_nascimento)->age; }
        return null;
    }
}