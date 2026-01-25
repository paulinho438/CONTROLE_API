<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthUserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function unauthorized()
    {
        return response()->json([
            'error' => 'Não autorizado'
        ], 401);
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required_without:login',
            'login' => 'required_without:usuario',
            'password' => 'required'
        ]);

        $login = $request->input('usuario', $request->input('login'));
        $result = $this->service->login($login, $request->input('password'));

        return response()->json([
            'token' => $result['token'],
            'user' => new AuthUserResource($result['user'])
        ]);
    }

    public function validateToken()
    {
        $user = $this->service->usuarioAtual();

        return response()->json([
            'user' => new AuthUserResource($user)
        ]);
    }

    public function logout()
    {
        $this->service->logout();

        return response()->json(['ok' => true]);
    }
}
