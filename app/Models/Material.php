<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materiais';

    protected $fillable = [
        'grupo_id',
        'nome',
        'aplicacao',
        'cor_predominante',
        'comprimento_m',
        'largura_m',
        'altura_m',
        'massa_kg',
        'densidade_kmm',
        'estoque_previsto'
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }
}

