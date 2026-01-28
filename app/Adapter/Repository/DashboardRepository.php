<?php

namespace App\Adapter\Repository;

use App\Core\Ports\Driven\DashboardRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function resumoGeral(): array
    {
        return [
            'total_entradas' => (int) DB::table('entradas')->sum('quantidade'),
            'total_saidas' => (int) DB::table('saidas')->sum('quantidade'),
            'total_notas_fiscais' => (int) DB::table('notas_fiscais')->count(),
            'total_materiais' => (int) DB::table('materiais')->count(),
        ];
    }

    public function resumoEstoque(array $grupoIds, array $patioIds): array
    {
        $patiosQuery = DB::table('patios')->select('id', 'nome');
        if (!empty($patioIds)) {
            $patiosQuery->whereIn('id', $patioIds);
        }
        $patios = $patiosQuery->orderBy('nome')->get();

        $gruposQuery = DB::table('grupos')->select('id', 'nome');
        if (!empty($grupoIds)) {
            $gruposQuery->whereIn('id', $grupoIds);
        }
        $grupos = $gruposQuery->orderBy('nome')->get();

        $materiais = DB::table('materiais')
            ->select('id', 'grupo_id', 'nome', 'estoque_previsto')
            ->whereIn('grupo_id', $grupos->pluck('id'))
            ->orderBy('nome')
            ->get();

        $entradasQuery = DB::table('entradas')
            ->select('material_id', 'patio_id', DB::raw('SUM(quantidade) as total'))
            ->whereIn('material_id', $materiais->pluck('id'))
            ->groupBy('material_id', 'patio_id');

        if (!empty($patioIds)) {
            $entradasQuery->whereIn('patio_id', $patioIds);
        }

        $entradas = $entradasQuery->get();

        $saidasQuery = DB::table('saidas')
            ->select('material_id', DB::raw('SUM(quantidade) as total'))
            ->whereIn('material_id', $materiais->pluck('id'))
            ->groupBy('material_id');

        $saidas = $saidasQuery->get();

        return [
            'patios' => $patios,
            'grupos' => $grupos,
            'materiais' => $materiais,
            'entradas' => $entradas,
            'saidas' => $saidas,
        ];
    }
}

