<?php

namespace App\Http\Controllers;

use App\Http\Resources\GrupoResource;
use App\Services\GrupoService;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    private GrupoService $service;

    public function __construct(GrupoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return GrupoResource::collection($this->service->listar());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:150',
            'data_cadastro' => 'nullable|date'
        ]);

        return new GrupoResource($this->service->criar($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:150',
            'data_cadastro' => 'nullable|date'
        ]);

        return new GrupoResource($this->service->atualizar((int) $id, $data));
    }

    public function destroy($id)
    {
        $this->service->excluir((int) $id);

        return response()->json(['ok' => true]);
    }
}

