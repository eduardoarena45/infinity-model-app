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

    protected $fillable = [
        'user_id',
        'nome_artistico',
        'imagem_principal_url',
        'data_nascimento',
        'cidade',
        'estado',
        'descricao_curta',
        'valor_hora',
        'whatsapp',
        'is_verified',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // CORREÇÃO: Mudamos 'string' para 'array'.
        // Isto diz ao Laravel para tratar o campo da imagem como um array,
        // o que é compatível com o componente de upload do Filament.
        'imagem_principal_url' => 'array',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function midias(): HasMany
    {
        return $this->hasMany(Midia::class);
    }

    public function servicos(): BelongsToMany
    {
        return $this->belongsToMany(Servico::class, 'acompanhante_servico');
    }

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class);
    }

    public function getNotaMediaAttribute(): float
    {
        return round($this->avaliacoes()->avg('nota'), 1);
    }

    public function getIdadeAttribute(): ?int
    {
        if ($this->data_nascimento) {
            return Carbon::parse($this->data_nascimento)->age;
        }
        return null;
    }
}
