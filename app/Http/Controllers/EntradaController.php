<?php

namespace App\Http\Controllers;

use App\Http\Resources\EntradaResource;
use App\Services\EntradaService;
use Illuminate\Http\Request;

class EntradaController extends Controller
{
    private EntradaService $service;

    public function __construct(EntradaService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return EntradaResource::collection($this->service->listar());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'grupo_id' => 'required|integer',
            'material_id' => 'required|integer',
            'patio_id' => 'required|integer',
            'fornecedor_id' => 'nullable|integer',
            'nota_fiscal_id' => 'nullable|integer',
            'valor' => 'nullable|numeric',
            'data_emissao' => 'nullable|date',
            'quantidade' => 'required|integer|min:1',
            'unidade_medida_id' => 'required|integer',
            'data_recebimento' => 'nullable|date',
            'numero_romaneio' => 'nullable|string|max:60',
            'peso_nota' => 'nullable|numeric',
            'responsavel_colaborador_id' => 'nullable|integer'
        ]);

        return new EntradaResource($this->service->criar($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'grupo_id' => 'required|integer',
            'material_id' => 'required|integer',
            'patio_id' => 'required|integer',
            'fornecedor_id' => 'nullable|integer',
            'nota_fiscal_id' => 'nullable|integer',
            'valor' => 'nullable|numeric',
            'data_emissao' => 'nullable|date',
            'quantidade' => 'required|integer|min:1',
            'unidade_medida_id' => 'required|integer',
            'data_recebimento' => 'nullable|date',
            'numero_romaneio' => 'nullable|string|max:60',
            'peso_nota' => 'nullable|numeric',
            'responsavel_colaborador_id' => 'nullable|integer'
        ]);

        return new EntradaResource($this->service->atualizar((int) $id, $data));
    }

    public function destroy($id)
    {
        $this->service->excluir((int) $id);

        return response()->json(['ok' => true]);
    }
}

