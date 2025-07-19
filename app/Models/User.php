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

    public function assinatura(): HasOne
    {
        return $this->hasOne(Assinatura::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    /**
     * Retorna o limite de fotos que este usuário pode ter na galeria,
     * baseado na sua assinatura ativa.
     */
    public function getPhotoLimit(): int
    {
        // Carrega a assinatura ativa (que não expirou) e o seu plano.
        $assinaturaAtiva = $this->assinatura()->with('plano')
                                ->where('status', 'ativa')
                                ->where('data_fim', '>', now())
                                ->first();

        // Se encontrar uma assinatura ativa, retorna o limite do plano.
        if ($assinaturaAtiva) {
            return $assinaturaAtiva->plano->limite_fotos;
        }

        // Se não, busca o limite do plano Grátis no banco de dados.
        $planoGratis = Plano::where('slug', 'gratis')->first();
        
        // Retorna o limite do plano grátis, ou 4 como um padrão de segurança.
        return $planoGratis ? $planoGratis->limite_fotos : 4;
    }

    public function getPrivateAvatarUrlAttribute(): string
    {
        if ($this->private_avatar_path && Storage::disk('public')->exists($this->private_avatar_path)) {
            return Storage::url($this->private_avatar_path);
        }
        return 'https://placehold.co/100x100/4E2A51/FFFFFF?text=IM';
    }
}
