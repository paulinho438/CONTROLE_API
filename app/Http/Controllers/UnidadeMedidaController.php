<?php

namespace App\Http\Controllers;

use App\Http\Resources\UnidadeMedidaResource;
use App\Services\UnidadeMedidaService;
use Illuminate\Http\Request;

class UnidadeMedidaController extends Controller
{
    private UnidadeMedidaService $service;

    public function __construct(UnidadeMedidaService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return UnidadeMedidaResource::collection($this->service->listar());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'unidade' => 'required|string|max:50'
        ]);

        return new UnidadeMedidaResource($this->service->criar($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'unidade' => 'required|string|max:50'
        ]);

        return new UnidadeMedidaResource($this->service->atualizar((int) $id, $data));
    }

    public function destroy($id)
    {
        $this->service->excluir((int) $id);

        return response()->json(['ok' => true]);
    }
}

