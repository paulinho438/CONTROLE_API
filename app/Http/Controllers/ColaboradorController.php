<?php

namespace App\Http\Controllers;

use App\Http\Resources\ColaboradorResource;
use App\Services\ColaboradorService;
use Illuminate\Http\Request;

class ColaboradorController extends Controller
{
    private ColaboradorService $service;

    public function __construct(ColaboradorService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return ColaboradorResource::collection($this->service->listar());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome_completo' => 'required|string|max:200',
            'funcao' => 'nullable|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'telefone' => 'nullable|string|max:40'
        ]);

        return new ColaboradorResource($this->service->criar($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nome_completo' => 'required|string|max:200',
            'funcao' => 'nullable|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'telefone' => 'nullable|string|max:40'
        ]);

        return new ColaboradorResource($this->service->atualizar((int) $id, $data));
    }

    public function destroy($id)
    {
        $this->service->excluir((int) $id);

        return response()->json(['ok' => true]);
    }
}

