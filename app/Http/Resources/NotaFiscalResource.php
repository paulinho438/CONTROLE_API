<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotaFiscalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fornecedor_id' => $this->fornecedor_id,
            'fornecedor' => $this->fornecedor?->razao_social,
            'razao_social' => $this->razao_social,
            'cnpj_cpf' => $this->cnpj_cpf,
            'numero_nota' => $this->numero_nota,
            'data_emissao' => $this->data_emissao,
            'data_recebimento' => $this->data_recebimento,
            'peso_nota' => $this->peso_nota,
            'valor' => $this->valor
        ];
    }
}

