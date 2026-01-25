<?php

namespace App\Http\Controllers;

use App\Http\Resources\PrevisaoResource;
use App\Services\PrevisaoService;
use Illuminate\Http\Request;

class PrevisaoController extends Controller
{
    private PrevisaoService $service;

    public function __construct(PrevisaoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return PrevisaoResource::collection($this->service->listar());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'grupo_id' => 'required|integer',
            'material_id' => 'required|integer',
            'patio_id' => 'required|integer',
            'quantidade_prevista' => 'required|integer|min:0',
            'unidade_medida_id' => 'required|integer'
        ]);

        return new PrevisaoResource($this->service->criar($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'grupo_id' => 'required|integer',
            'material_id' => 'required|integer',
            'patio_id' => 'required|integer',
            'quantidade_prevista' => 'required|integer|min:0',
            'unidade_medida_id' => 'required|integer'
        ]);

        return new PrevisaoResource($this->service->atualizar((int) $id, $data));
    }

    public function destroy($id)
    {
        $this->service->excluir((int) $id);

        return response()->json(['ok' => true]);
    }

    public function previsaoGeral(Request $request)
    {
        $previsoes = $this->service->listar();
        return PrevisaoResource::collection($previsoes);
    }

    public function previsaoPorPatio(Request $request)
    {
        $patioId = $request->input('patio_id');
        $filters = $patioId ? ['patio_id' => $patioId] : [];
        $previsoes = $this->service->listar($filters);
        return PrevisaoResource::collection($previsoes);
    }
}

