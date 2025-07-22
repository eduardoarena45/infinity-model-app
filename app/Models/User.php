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

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'private_avatar_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    public function acompanhante(): HasOne
    {
        return $this->hasOne(Acompanhante::class);
    }

    // --- RELAÇÃO CORRIGIDA PARA PEGAR A ASSINATURA ATIVA ---
    public function assinaturaAtiva(): HasOne
    {
        return $this->hasOne(Assinatura::class)
                    ->where('status', 'ativa')
                    ->where('data_fim', '>', now());
    }
    // --- FIM DA CORREÇÃO ---

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function getPhotoLimit(): int
    {
        // Agora usa a relação 'assinaturaAtiva'
        if ($this->assinaturaAtiva && $this->assinaturaAtiva->plano) {
            return $this->assinaturaAtiva->plano->limite_fotos;
        }

        // Se não, busca o limite do plano Grátis.
        $planoGratis = Plano::where('slug', 'gratis')->first();
        return $planoGratis ? $planoGratis->limite_fotos : 4;
    }

    // --- NOVO MÉTODO getVideoLimit ADICIONADO ---
    public function getVideoLimit(): int
    {
        // Usa a mesma lógica da assinatura ativa
        if ($this->assinaturaAtiva && $this->assinaturaAtiva->plano) {
            return $this->assinaturaAtiva->plano->limite_videos;
        }
        
        // Se não tiver assinatura ativa, busca o limite do plano Grátis.
        $planoGratis = Plano::where('slug', 'gratis')->first();
        return $planoGratis ? $planoGratis->limite_videos : 0; // Padrão 0 para vídeos no plano grátis
    }
    // --- FIM DO NOVO MÉTODO ---


    public function getPrivateAvatarUrlAttribute(): string
    {
        if ($this->private_avatar_path && Storage::disk('public')->exists($this->private_avatar_path)) {
            return Storage::url($this->private_avatar_path);
        }
        return 'https://placehold.co/100x100/4E2A51/FFFFFF?text=IM';
    }
}