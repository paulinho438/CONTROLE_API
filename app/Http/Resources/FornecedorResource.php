<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FornecedorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'razao_social' => $this->razao_social,
            'cnpj_cpf' => $this->cnpj_cpf,
            'endereco' => $this->endereco,
            'numero' => $this->numero,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'pais' => $this->pais,
            'telefone' => $this->telefone,
            'email' => $this->email
        ];
    }
}

