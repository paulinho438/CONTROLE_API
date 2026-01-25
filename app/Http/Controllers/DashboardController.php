<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private DashboardService $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function resumoGeral()
    {
        return response()->json($this->service->resumoGeral());
    }

    public function resumoEstoque(Request $request)
    {
        $grupoIds = $request->input('grupos', []);
        $patioIds = $request->input('patios', []);

        return response()->json($this->service->resumoEstoque($grupoIds, $patioIds));
    }

    public function balanco(Request $request)
    {
        $grupoIds = $request->input('grupos', []);
        $materialIds = $request->input('materiais', []);

        return response()->json($this->service->resumoEstoque($grupoIds, [], $materialIds));
    }
}
