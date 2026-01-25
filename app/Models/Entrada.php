<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

    protected $table = 'entradas';

    protected $fillable = [
        'grupo_id',
        'material_id',
        'patio_id',
        'fornecedor_id',
        'nota_fiscal_id',
        'valor',
        'data_emissao',
        'quantidade',
        'unidade_medida_id',
        'data_recebimento',
        'numero_romaneio',
        'peso_nota',
        'responsavel_colaborador_id'
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

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }

    public function notaFiscal()
    {
        return $this->belongsTo(NotaFiscal::class, 'nota_fiscal_id');
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

