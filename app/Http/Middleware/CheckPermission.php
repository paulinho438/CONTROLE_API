<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'Não autenticado'], 401);
        }

        // Obter todas as permissões do usuário através dos grupos
        $permissions = [];
        foreach ($user->groups as $group) {
            foreach ($group->items as $item) {
                $permissions[] = $item->slug;
            }
        }

        $permissions = array_unique($permissions);

        // Verificar se o usuário tem a permissão necessária
        if (!in_array($permission, $permissions)) {
            return response()->json([
                'error' => 'Acesso negado',
                'message' => 'Você não tem permissão para realizar esta ação.'
            ], 403);
        }

        return $next($request);
    }
}

