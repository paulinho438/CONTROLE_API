<?php

namespace App\Http\Controllers;

use App\Http\Resources\FornecedorResource;
use App\Services\FornecedorService;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    private FornecedorService $service;

    public function __construct(FornecedorService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return FornecedorResource::collection($this->service->listar());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'razao_social' => 'required|string|max:200',
            'cnpj_cpf' => 'nullable|string|max:30',
            'endereco' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:30',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:60',
            'pais' => 'nullable|string|max:60',
            'telefone' => 'nullable|string|max:40',
            'email' => 'nullable|string|max:150'
        ]);

        return new FornecedorResource($this->service->criar($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'razao_social' => 'required|string|max:200',
            'cnpj_cpf' => 'nullable|string|max:30',
            'endereco' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:30',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:60',
            'pais' => 'nullable|string|max:60',
            'telefone' => 'nullable|string|max:40',
            'email' => 'nullable|string|max:150'
        ]);

        return new FornecedorResource($this->service->atualizar((int) $id, $data));
    }

    public function destroy($id)
    {
        $this->service->excluir((int) $id);

        return response()->json(['ok' => true]);
    }
}
