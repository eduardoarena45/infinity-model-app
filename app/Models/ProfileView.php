<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileView extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'acompanhante_id',
    ];

    /**
     * Get the acompanhante that owns the view.
     */
    public function acompanhante(): BelongsTo
    {
        return $this->belongsTo(Acompanhante::class);
    }
}