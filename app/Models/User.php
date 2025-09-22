<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Models\Plano;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [ 'name', 'email', 'password', 'is_admin', 'private_avatar_path', ];
    protected $hidden = [ 'password', 'remember_token', ];
    protected $casts = [ 'email_verified_at' => 'datetime', 'password' => 'hashed', 'is_admin' => 'boolean', ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    public function acompanhante(): HasOne
    {
        return $this->hasOne(Acompanhante::class);
    }

    // --- RELAÇÃO CORRIGIDA PARA PEGAR A ASSINATURA ATIVA (COM SUPORTE A PLANOS VITALÍCIOS) ---
    public function assinaturaAtiva(): HasOne
    {
        return $this->hasOne(Assinatura::class)
            ->where('status', 'ativa')
            // Uma assinatura é ativa se:
            // 1. A data de fim está no futuro
            // OU
            // 2. A data de fim é NULA (vitalícia)
            ->where(function ($query) {
                $query->where('data_fim', '>', now())
                      ->orWhereNull('data_fim');
            });
    }

    public function assinaturas(): HasMany
    {
        return $this->hasMany(Assinatura::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function getPhotoLimit(): int
    {
        if ($this->assinaturaAtiva && $this->assinaturaAtiva->plano) {
            return $this->assinaturaAtiva->plano->limite_fotos;
        }
        $planoGratis = Plano::where('slug', 'gratis')->first();
        return $planoGratis ? $planoGratis->limite_fotos : 4;
    }

    public function getVideoLimit(): int
    {
        if ($this->assinaturaAtiva && $this->assinaturaAtiva->plano) {
            return $this->assinaturaAtiva->plano->limite_videos;
        }
        $planoGratis = Plano::where('slug', 'gratis')->first();
        return $planoGratis ? $planoGratis->limite_videos : 0;
    }

    public function getDescricaoLimit(): ?int
    {
        if ($this->assinaturaAtiva && $this->assinaturaAtiva->plano) {
            return $this->assinaturaAtiva->plano->limite_descricao ?: null;
        }
        $planoGratis = Plano::where('slug', 'gratis')->first();
        return $planoGratis ? $planoGratis->limite_descricao : null;
    }

    public function getPrivateAvatarUrlAttribute(): string
    {
        if ($this->private_avatar_path && Storage::disk('public')->exists($this->private_avatar_path)) {
            return Storage::url($this->private_avatar_path);
        }
        return 'https://placehold.co/100x100/4E2A51/FFFFFF?text=IM';
    }
}

