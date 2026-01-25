<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PrevisaoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'grupo_id' => $this->grupo_id,
            'grupo' => $this->grupo?->nome,
            'material_id' => $this->material_id,
            'material' => $this->material?->nome,
            'patio_id' => $this->patio_id,
            'patio' => $this->patio?->nome,
            'quantidade_prevista' => $this->quantidade_prevista,
            'unidade_medida_id' => $this->unidade_medida_id,
            'unidade_medida' => $this->unidadeMedida?->unidade
        ];
    }
}

