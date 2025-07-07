<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin', // Mantém o campo de admin
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean', // Mantém o cast de admin
        ];
    }

    /**
     * Relação: um User tem um perfil de Acompanhante.
     */
    public function acompanhante(): HasOne
    {
        return $this->hasOne(Acompanhante::class);
    }

    /**
     * NOVA RELAÇÃO: um User tem uma Assinatura.
     */
    public function assinatura(): HasOne
    {
        return $this->hasOne(Assinatura::class);
    }
}