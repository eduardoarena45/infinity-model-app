<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

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

    public function acompanhante()
    {
        return $this->hasOne(Acompanhante::class);
    }

    public function assinatura()
    {
        return $this->hasOne(Assinatura::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    /**
     * NOVO MÉTODO
     * Retorna o limite de fotos que este usuário pode ter na galeria.
     */
    public function getPhotoLimit(): int
    {
        // No futuro, você poderá adicionar lógica de planos aqui.
        // Por enquanto, o limite padrão é 4 para todos.
        return 4;
    }

    public function getPrivateAvatarUrlAttribute(): string
    {
        if ($this->private_avatar_path && Storage::disk('public')->exists($this->private_avatar_path)) {
            return Storage::url($this->private_avatar_path);
        }
        return 'https://placehold.co/100x100/4E2A51/FFFFFF?text=IM';
    }
}