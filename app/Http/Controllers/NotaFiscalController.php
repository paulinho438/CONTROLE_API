<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotaFiscalResource;
use App\Services\NotaFiscalService;
use Illuminate\Http\Request;

class NotaFiscalController extends Controller
{
    private NotaFiscalService $service;

    public function __construct(NotaFiscalService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return NotaFiscalResource::collection($this->service->listar());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fornecedor_id' => 'nullable|integer',
            'razao_social' => 'nullable|string|max:200',
            'cnpj_cpf' => 'nullable|string|max:30',
            'numero_nota' => 'nullable|string|max:60',
            'data_emissao' => 'nullable|date',
            'data_recebimento' => 'nullable|date',
            'peso_nota' => 'nullable|numeric',
            'valor' => 'nullable|numeric'
        ]);

        return new NotaFiscalResource($this->service->criar($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'fornecedor_id' => 'nullable|integer',
            'razao_social' => 'nullable|string|max:200',
            'cnpj_cpf' => 'nullable|string|max:30',
            'numero_nota' => 'nullable|string|max:60',
            'data_emissao' => 'nullable|date',
            'data_recebimento' => 'nullable|date',
            'peso_nota' => 'nullable|numeric',
            'valor' => 'nullable|numeric'
        ]);

        return new NotaFiscalResource($this->service->atualizar((int) $id, $data));
    }

    public function destroy($id)
    {
        $this->service->excluir((int) $id);

        return response()->json(['ok' => true]);
    }
}

