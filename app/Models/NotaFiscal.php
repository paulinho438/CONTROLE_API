<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaFiscal extends Model
{
    use HasFactory;

    protected $table = 'notas_fiscais';

    protected $fillable = [
        'fornecedor_id',
        'razao_social',
        'cnpj_cpf',
        'numero_nota',
        'data_emissao',
        'data_recebimento',
        'peso_nota',
        'valor'
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }
}

