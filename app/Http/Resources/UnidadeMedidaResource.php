<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UnidadeMedidaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'unidade' => $this->unidade
        ];
    }
}

