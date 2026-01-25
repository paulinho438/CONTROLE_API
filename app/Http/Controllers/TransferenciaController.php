<?php

namespace App\Http\Controllers;

use App\Http\Resources\SaidaResource;
use App\Services\SaidaService;
use Illuminate\Http\Request;

class TransferenciaController extends Controller
{
    private SaidaService $service;

    public function __construct(SaidaService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $transferencias = $this->service->listar(['tipo_movimentacao' => 'Transferência']);
        return SaidaResource::collection($transferencias);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'grupo_id' => 'required|integer',
            'material_id' => 'required|integer',
            'patio_id' => 'required|integer',
            'destino_patio_id' => 'required|integer',
            'tipo_movimentacao' => 'nullable|string|max:60',
            'data_saida' => 'nullable|date',
            'quantidade' => 'required|integer|min:1',
            'unidade_medida_id' => 'required|integer',
            'numero_romaneio' => 'nullable|string|max:60',
            'responsavel_colaborador_id' => 'nullable|integer',
            'observacao' => 'nullable|string'
        ]);

        // Garantir que é uma transferência
        $data['tipo_movimentacao'] = 'Transferência';

        return new SaidaResource($this->service->criar($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'grupo_id' => 'required|integer',
            'material_id' => 'required|integer',
            'patio_id' => 'required|integer',
            'destino_patio_id' => 'required|integer',
            'tipo_movimentacao' => 'nullable|string|max:60',
            'data_saida' => 'nullable|date',
            'quantidade' => 'required|integer|min:1',
            'unidade_medida_id' => 'required|integer',
            'numero_romaneio' => 'nullable|string|max:60',
            'responsavel_colaborador_id' => 'nullable|integer',
            'observacao' => 'nullable|string'
        ]);

        // Garantir que é uma transferência
        $data['tipo_movimentacao'] = 'Transferência';

        return new SaidaResource($this->service->atualizar((int) $id, $data));
    }

    public function destroy($id)
    {
        $this->service->excluir((int) $id);
        return response()->json(['ok' => true]);
    }
}

