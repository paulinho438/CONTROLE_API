<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Previsao extends Model
{
    use HasFactory;

    protected $table = 'previsoes';

    protected $fillable = [
        'grupo_id',
        'material_id',
        'patio_id',
        'quantidade_prevista',
        'unidade_medida_id'
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function patio()
    {
        return $this->belongsTo(Patio::class, 'patio_id');
    }

    public function unidadeMedida()
    {
        return $this->belongsTo(UnidadeMedida::class, 'unidade_medida_id');
    }
}

