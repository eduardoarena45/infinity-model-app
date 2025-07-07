<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Plano extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'preco', 'limite_fotos', 'limite_videos', 'permite_destaque'];
}