<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RomaneioResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray($request)
    {
        $saidas = $this->resource;
        
        if ($saidas->isEmpty()) {
            return [
                'numero_romaneio' => null,
                'cabecalho' => null,
                'materiais' => []
            ];
        }

        $primeiraSaida = $saidas->first();
        
        return [
            'numero_romaneio' => $primeiraSaida->numero_romaneio,
            'cabecalho' => [
                'numero_romaneio' => $primeiraSaida->numero_romaneio,
                'data_saida' => $primeiraSaida->data_saida,
                'patio' => $primeiraSaida->patio?->nome,
                'patio_id' => $primeiraSaida->patio_id,
                'destino' => $primeiraSaida->destino ?? $primeiraSaida->destinoPatio?->nome,
                'destino_patio_id' => $primeiraSaida->destino_patio_id,
                'tipo_movimentacao' => $primeiraSaida->tipo_movimentacao,
                'responsavel' => $primeiraSaida->responsavel?->nome_completo,
                'responsavel_id' => $primeiraSaida->responsavel_colaborador_id,
                'grupo' => $primeiraSaida->grupo?->nome,
                'grupo_id' => $primeiraSaida->grupo_id,
            ],
            'materiais' => $saidas->map(function ($saida) {
                return [
                    'id' => $saida->id,
                    'material' => $saida->material?->nome,
                    'material_id' => $saida->material_id,
                    'quantidade' => $saida->quantidade,
                    'unidade_medida' => $saida->unidadeMedida?->unidade,
                    'unidade_medida_id' => $saida->unidade_medida_id,
                ];
            })->values()->toArray()
        ];
    }
}
