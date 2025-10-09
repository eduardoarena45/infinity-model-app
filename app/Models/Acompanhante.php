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
        // =======================================================
        // =================== INÍCIO DA CORREÇÃO ==================
        // Adicionamos a permissão que faltava aqui.
        'foto_verificacao_path',
        // ==================== FIM DA CORREÇÃO =====================
        'data_nascimento',
        'cidade_id',
        'descricao',
        'valor_hora',
        'valor_15_min',
        'valor_30_min',
        'valor_pernoite',
        'whatsapp',
        'is_verified',
        'is_featured',
        'status',
        'horarios_atendimento',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'data_nascimento' => 'date',
        'valor_hora' => 'decimal:2',
        'valor_15_min' => 'decimal:2',
        'valor_30_min' => 'decimal:2',
        'valor_pernoite' => 'decimal:2',
        'horarios_atendimento' => 'array',
    ];

    public function getFotoPrincipalUrlAttribute(): string
    {
        if ($this->foto_principal_path && Storage::disk('public')->exists($this->foto_principal_path)) {
            return Storage::url($this->foto_principal_path);
        }
        return 'https://placehold.co/400x600/663399/FFFFFF?text=Sem+Foto';
    }

    public function getFotoVerificacaoUrlAttribute(): ?string
    {
        if (!$this->foto_verificacao_path) {
            return null;
        }

        if (Storage::disk('public')->exists($this->foto_verificacao_path)) {
            return Storage::url($this->foto_verificacao_path);
        }

        return null;
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

    public function midias(): HasMany
    {
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

    public function profileViews(): HasMany
    {
        return $this->hasMany(ProfileView::class);
    }

    /**
     * Verifica se o perfil tem todos os dados mínimos para ser listado publicamente.
     * Isto inclui ter pelo menos uma foto na galeria.
     *
     * @return bool
     */
    public function isPubliclyReady(): bool
    {
        // Verifica se os campos de texto obrigatórios estão preenchidos
        $hasRequiredFields = $this->nome_artistico &&
                             $this->foto_principal_path &&
                             $this->cidade_id &&
                             $this->descricao &&
                             $this->whatsapp &&
                             $this->valor_hora;

        if (!$hasRequiredFields) {
            return false;
        }

        // A verificação final e mais importante: tem pelo menos uma foto na galeria?
        $hasGalleryPhoto = $this->midias()->where('type', 'image')->exists();

        return $hasGalleryPhoto;
    }
}

