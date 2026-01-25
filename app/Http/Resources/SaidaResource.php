<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaidaResource extends JsonResource
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
            'destino_patio_id' => $this->destino_patio_id,
            'destino_patio' => $this->destinoPatio?->nome,
            'tipo_movimentacao' => $this->tipo_movimentacao,
            'nota_fiscal' => $this->nota_fiscal,
            'valor' => $this->valor,
            'data_saida' => $this->data_saida,
            'quantidade' => $this->quantidade,
            'unidade_medida_id' => $this->unidade_medida_id,
            'unidade_medida' => $this->unidadeMedida?->unidade,
            'numero_romaneio' => $this->numero_romaneio,
            'responsavel_colaborador_id' => $this->responsavel_colaborador_id,
            'responsavel' => $this->responsavel?->nome_completo,
            'destino' => $this->destino,
            'observacao' => $this->observacao
        ];
    }
}

