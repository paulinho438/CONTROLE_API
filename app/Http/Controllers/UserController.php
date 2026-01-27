<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return UserResource::collection($this->service->listar());
    }

    public function show($id)
    {
        $user = $this->service->obter((int) $id);

        return new UserResource($user);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'login' => 'required|string|max:50|unique:users,login',
            'nome_completo' => 'required|string|max:150',
            'email' => 'nullable|email|max:255',
            'password' => 'required|string|min:6',
            'status' => 'required|in:A,I',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:permgroups,id'
        ]);

        $user = $this->service->criar($data);

        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'login' => 'required|string|max:50|unique:users,login,' . $id,
            'nome_completo' => 'required|string|max:150',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:A,I',
            'groups' => 'nullable|array',
            'groups.*' => 'exists:permgroups,id'
        ]);

        $user = $this->service->atualizar((int) $id, $data);

        return new UserResource($user);
    }

    public function destroy($id)
    {
        $this->service->excluir((int) $id);

        return response()->json(['ok' => true]);
    }
}
