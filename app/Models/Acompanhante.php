<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // Importe a relação

class Acompanhante extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','nome_artistico','imagem_principal_url','data_nascimento','cidade','estado','descricao_curta','valor_hora','whatsapp',];
    protected $casts = ['imagem_principal_url' => 'string',];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function midias(): HasMany { return $this->hasMany(Midia::class); }

    /**
     * NOVA RELAÇÃO: Uma Acompanhante pertence a Muitos Serviços.
     */
    public function servicos(): BelongsToMany
    {
        return $this->belongsToMany(Servico::class, 'acompanhante_servico');
    }

    public function getIdadeAttribute(): ?int
    {
        if ($this->data_nascimento) { return Carbon::parse($this->data_nascimento)->age; }
        return null;
    }
}
