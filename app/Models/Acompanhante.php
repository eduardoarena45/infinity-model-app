<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Acompanhante extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
        'status', // Inclui o novo campo de status
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'imagem_principal_url' => 'array',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Accessor para a URL completa da foto principal.
     */
    public function getFotoPrincipalUrlCompletaAttribute(): ?string
    {
        $path = is_array($this->imagem_principal_url) ? ($this->imagem_principal_url[0] ?? null) : $this->imagem_principal_url;

        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return 'https://placehold.co/400x600/ccc/333?text=Sem+Foto';
    }

    /**
     * Accessor para a média de notas das avaliações.
     */
    public function getNotaMediaAttribute(): float
    {
        return round($this->avaliacoes()->avg('nota'), 1);
    }

    /**
     * Accessor para calcular a idade.
     */
    public function getIdadeAttribute(): ?int
    {
        if ($this->data_nascimento) {
            return Carbon::parse($this->data_nascimento)->age;
        }
        return null;
    }

    // --- RELAÇÕES ---

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
}
