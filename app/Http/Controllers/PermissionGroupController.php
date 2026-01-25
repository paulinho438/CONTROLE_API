<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionGroupResource;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionGroupController extends Controller
{
    private PermissionService $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return PermissionGroupResource::collection($this->service->listarGrupos());
    }

    public function show($id)
    {
        $group = $this->service->obterGrupo((int) $id);
        if (!$group) {
            return response()->json(['message' => 'Grupo não encontrado.'], 404);
        }

        return new PermissionGroupResource($group);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'permitems' => 'array'
        ]);

        $group = $this->service->criarGrupo($data);

        return new PermissionGroupResource($group);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'permitems' => 'array'
        ]);

        $group = $this->service->atualizarGrupo((int) $id, $data);

        return new PermissionGroupResource($group);
    }

    public function destroy($id)
    {
        $this->service->excluirGrupo((int) $id);

        return response()->json(['ok' => true]);
    }
}

