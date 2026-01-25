<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ColaboradorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nome_completo' => $this->nome_completo,
            'funcao' => $this->funcao,
            'departamento' => $this->departamento,
            'telefone' => $this->telefone
        ];
    }
}

