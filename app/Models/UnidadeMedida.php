<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadeMedida extends Model
{
    use HasFactory;

    protected $table = 'unidades_medida';

    protected $fillable = [
        'unidade'
    ];

    public function getDateFormat()
    {
        return 'Y-m-d\TH:i:s.v';
    }
}

