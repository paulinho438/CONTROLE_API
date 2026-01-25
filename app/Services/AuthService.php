<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(string $login, string $password): array
    {
        $token = Auth::attempt([
            'login' => $login,
            'password' => $password
        ]);

        if (!$token) {
            throw new BusinessException('Usuário ou senha inválidos.');
        }

        $user = auth()->user()->load('groups.items');
        if ($user->status === 'I') {
            throw new BusinessException('Usuário inativo.');
        }

        return [
            'token' => $token,
            'user' => $user
        ];
    }

    public function logout(): void
    {
        auth()->logout();
    }

    public function usuarioAtual()
    {
        return auth()->user()->load('groups.items');
    }
}

