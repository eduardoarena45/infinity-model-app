<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Acompanhante extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nome_artistico',
        'imagem_principal_url',
        'data_nascimento',
        'cidade',
        'estado',
        'descricao_curta',
        'valor_hora',
        'whatsapp',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Isso garante que o Laravel sempre trate este campo como uma string (texto),
        // o que é o correto para o caminho do arquivo salvo pelo Filament.
        'imagem_principal_url' => 'string',
    ];

    /**
     * Define the relationship: an Acompanhante profile belongs to a User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * "Magic" attribute to calculate the age from the date of birth.
     * Now you can use $profile->age and it will calculate on the fly!
     */
    public function getIdadeAttribute(): ?int
    {
        if ($this->data_nascimento) {
            return Carbon::parse($this->data_nascimento)->age;
        }
        return null;
    }
}
