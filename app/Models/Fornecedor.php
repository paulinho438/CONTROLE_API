<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;

    protected $table = 'fornecedores';

    protected $fillable = [
        'razao_social',
        'cnpj_cpf',
        'endereco',
        'numero',
        'cidade',
        'estado',
        'pais',
        'telefone',
        'email'
    ];

    public function getDateFormat()
    {
        return 'Y-m-d\TH:i:s.v';
    }
}
