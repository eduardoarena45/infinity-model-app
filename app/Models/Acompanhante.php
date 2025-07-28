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

    protected $table = 'acompanhantes';

    protected $fillable = [
        'user_id',
        'nome_artistico',
        'genero',
        'foto_principal_path',
        'data_nascimento',
        'cidade_id',
        'descricao',
        'valor_hora',
        'valor_15_min', // <-- Adicionado
        'valor_30_min', // <-- Adicionado
        'valor_pernoite', // <-- Adicionado
        'whatsapp',
        'is_verified',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'data_nascimento' => 'date',
        'valor_hora' => 'decimal:2',
        'valor_15_min' => 'decimal:2', // <-- Adicionado
        'valor_30_min' => 'decimal:2', // <-- Adicionado
        'valor_pernoite' => 'decimal:2', // <-- Adicionado
    ];

    public function getFotoPrincipalUrlAttribute(): string
    {
        if ($this->foto_principal_path && Storage::disk('public')->exists($this->foto_principal_path)) {
            return Storage::url($this->foto_principal_path);
        }
        return 'https://placehold.co/400x600/663399/FFFFFF?text=Sem+Foto';
    }

    public function getIdadeAttribute(): ?int
    {
        return $this->data_nascimento ? Carbon::parse($this->data_nascimento)->age : null;
    }

    // --- RELAÇÕES ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class);
    }

    /**
     * Um perfil de Acompanhante tem muitas mídias na galeria.
     * NOME CORRIGIDO PARA 'midias' PARA FUNCIONAR COM O FILAMENT RELATION MANAGER
     */
    public function midias(): HasMany
    {
        // Esta relação funciona porque tanto Acompanhante quanto Media estão ligados pelo user_id
        return $this->hasMany(Media::class, 'user_id', 'user_id');
    }

    public function servicos(): BelongsToMany
    {
        return $this->belongsToMany(Servico::class, 'acompanhante_servico');
    }

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class);
    }

    /**
     * Get all of the profile views for the Acompanhante.
     * ESTA É A NOVA FUNÇÃO PARA AS ESTATÍSTICAS
     */
    public function profileViews(): HasMany
    {
        return $this->hasMany(ProfileView::class);
    }
}
