<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assinatura extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     * Corrigimos esta lista para incluir os novos campos.
     */
    protected $fillable = [
        'user_id', 
        'plano_id', 
        'status',       // <-- Adicionado para permitir salvar o status
        'data_inicio',  // <-- Adicionado para permitir salvar a data de início
        'data_fim',     // <-- Corrigido de 'data_expiracao' para 'data_fim'
    ];

    /**
     * Define como os atributos devem ser convertidos.
     */
    protected $casts = [
        'data_inicio' => 'datetime', // <-- Adicionado
        'data_fim'    => 'datetime', // <-- Corrigido de 'data_expiracao'
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    
    public function plano(): BelongsTo { return $this->belongsTo(Plano::class); }
}
