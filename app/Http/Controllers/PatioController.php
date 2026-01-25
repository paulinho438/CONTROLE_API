<?php

namespace App\Http\Controllers;

use App\Http\Resources\PatioResource;
use App\Services\PatioService;
use Illuminate\Http\Request;

class PatioController extends Controller
{
    private PatioService $service;

    public function __construct(PatioService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return PatioResource::collection($this->service->listar());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:150'
        ]);

        return new PatioResource($this->service->criar($data));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:150'
        ]);

        return new PatioResource($this->service->atualizar((int) $id, $data));
    }

    public function destroy($id)
    {
        $this->service->excluir((int) $id);

        return response()->json(['ok' => true]);
    }
}

