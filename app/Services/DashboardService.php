<?php

namespace App\Services;

use App\Core\Ports\Driven\DashboardRepositoryInterface;

class DashboardService
{
    private DashboardRepositoryInterface $repository;

    public function __construct(DashboardRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function resumoGeral(): array
    {
        return $this->repository->resumoGeral();
    }

    public function resumoEstoque(array $grupoIds = [], array $patioIds = [], array $materialIds = []): array
    {
        $data = $this->repository->resumoEstoque($grupoIds, $patioIds);
        $patios = $data['patios'];
        $grupos = $data['grupos'];
        $materiais = $data['materiais'];
        
        // Filtrar por materiais se especificado
        if (!empty($materialIds)) {
            $materiais = $materiais->whereIn('id', $materialIds);
        }
        $entradas = $data['entradas'];

        $entradasMap = [];
        foreach ($entradas as $entrada) {
            $entradasMap[$entrada->material_id][$entrada->patio_id] = (int) $entrada->total;
        }

        $resultadoGrupos = [];
        foreach ($grupos as $grupo) {
            $materiaisDoGrupo = $materiais->where('grupo_id', $grupo->id);
            
            // Se filtrou por materiais e não há materiais neste grupo, pular
            if (!empty($materialIds) && $materiaisDoGrupo->isEmpty()) {
                continue;
            }
            
            $materiaisDoGrupo = $materiaisDoGrupo->values();
            $materiaisResposta = [];

            foreach ($materiaisDoGrupo as $material) {
                $entradasPorPatio = [];
                $totalRecebido = 0;

                foreach ($patios as $patio) {
                    $quantidade = $entradasMap[$material->id][$patio->id] ?? 0;
                    $totalRecebido += $quantidade;
                    $entradasPorPatio[] = [
                        'patio_id' => $patio->id,
                        'patio' => $patio->nome,
                        'quantidade' => $quantidade
                    ];
                }

                $materiaisResposta[] = [
                    'id' => $material->id,
                    'material' => $material->nome,
                    'estoque_previsto' => (int) $material->estoque_previsto,
                    'entradas_por_patio' => $entradasPorPatio,
                    'total_recebido' => $totalRecebido,
                    'diferenca' => $totalRecebido - (int) $material->estoque_previsto
                ];
            }

            $resultadoGrupos[] = [
                'id' => $grupo->id,
                'nome' => $grupo->nome,
                'materiais' => $materiaisResposta
            ];
        }

        return [
            'patios' => $patios,
            'grupos' => $resultadoGrupos
        ];
    }
}

