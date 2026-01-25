<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'grupo_id' => $this->grupo_id,
            'grupo' => $this->grupo?->nome,
            'nome' => $this->nome,
            'aplicacao' => $this->aplicacao,
            'cor_predominante' => $this->cor_predominante,
            'comprimento_m' => $this->comprimento_m,
            'largura_m' => $this->largura_m,
            'altura_m' => $this->altura_m,
            'massa_kg' => $this->massa_kg,
            'densidade_kmm' => $this->densidade_kmm,
            'estoque_previsto' => $this->estoque_previsto
        ];
    }
}

