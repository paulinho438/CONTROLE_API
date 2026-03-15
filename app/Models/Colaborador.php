<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    use HasFactory;

    protected $table = 'colaboradores';

    protected $fillable = [
        'nome_completo',
        'funcao',
        'departamento',
        'telefone'
    ];

    public function getDateFormat()
    {
        return 'Y-m-d\TH:i:s.v';
    }
}

