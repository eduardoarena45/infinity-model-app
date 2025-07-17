<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'path',
        'type',
        'status',
    ];

    /**
     * Define o relacionamento inverso: Uma Mídia pertence a um Usuário.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}