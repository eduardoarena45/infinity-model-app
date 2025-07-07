<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Assinatura extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'plano_id', 'data_expiracao'];
    protected $casts = ['data_expiracao' => 'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function plano(): BelongsTo { return $this->belongsTo(Plano::class); }
}