<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saida extends Model
{
    use HasFactory;

    protected $table = 'saidas';

    protected $fillable = [
        'grupo_id',
        'material_id',
        'patio_id',
        'destino_patio_id',
        'tipo_movimentacao',
        'nota_fiscal',
        'valor',
        'data_saida',
        'quantidade',
        'unidade_medida_id',
        'numero_romaneio',
        'responsavel_colaborador_id',
        'destino',
        'observacao'
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

    public function destinoPatio()
    {
        return $this->belongsTo(Patio::class, 'destino_patio_id');
    }

    public function unidadeMedida()
    {
        return $this->belongsTo(UnidadeMedida::class, 'unidade_medida_id');
    }

    public function responsavel()
    {
        return $this->belongsTo(Colaborador::class, 'responsavel_colaborador_id');
    }
}

