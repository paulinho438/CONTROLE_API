<?php

namespace App\Http\Controllers;

use App\Http\Resources\MaterialResource;
use App\Services\MaterialService;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    private MaterialService $service;

    public function __construct(MaterialService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return MaterialResource::collection($this->service->listar());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'grupo_id' => 'required|integer',
            'nome' => 'required|string|max:200',
            'aplicacao' => 'nullable|string|max:255',
            'cor_predominante' => 'nullable|string|max:100',
            'comprimento_m' => 'nullable|numeric',
            'largura_m' => 'nullable|numeric',
            'altura_m' => 'nullable|numeric',
            'massa_kg' => 'nullable|numeric',
            'densidade_kmm' => 'nullable|numeric',
            'estoque_previsto' => 'nullable|integer'
        ]);

        return new MaterialResource($this->service->criar($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'grupo_id' => 'required|integer',
            'nome' => 'required|string|max:200',
            'aplicacao' => 'nullable|string|max:255',
            'cor_predominante' => 'nullable|string|max:100',
            'comprimento_m' => 'nullable|numeric',
            'largura_m' => 'nullable|numeric',
            'altura_m' => 'nullable|numeric',
            'massa_kg' => 'nullable|numeric',
            'densidade_kmm' => 'nullable|numeric',
            'estoque_previsto' => 'nullable|integer'
        ]);

        return new MaterialResource($this->service->atualizar((int) $id, $data));
    }

    public function destroy($id)
    {
        $this->service->excluir((int) $id);

        return response()->json(['ok' => true]);
    }
}

