<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntradaResource extends JsonResource
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
            'fornecedor_id' => $this->fornecedor_id,
            'fornecedor' => $this->fornecedor?->razao_social,
            'nota_fiscal_id' => $this->nota_fiscal_id,
            'valor' => $this->valor,
            'data_emissao' => $this->data_emissao,
            'quantidade' => $this->quantidade,
            'unidade_medida_id' => $this->unidade_medida_id,
            'unidade_medida' => $this->unidadeMedida?->unidade,
            'data_recebimento' => $this->data_recebimento,
            'numero_romaneio' => $this->numero_romaneio,
            'peso_nota' => $this->peso_nota,
            'responsavel_colaborador_id' => $this->responsavel_colaborador_id,
            'responsavel' => $this->responsavel?->nome_completo
        ];
    }
}

