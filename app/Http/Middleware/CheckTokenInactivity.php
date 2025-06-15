<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CheckTokenInactivity
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        $token = $user->currentAccessToken();

        if (!$token) {
            return response()->json(['message' => 'Token de acesso não encontrado.'], 401);
        }

        if ($token->expires_at && Carbon::parse($token->expires_at)->isPast()) {
            $token->delete(); // Invalida o token
            return response()->json(['message' => 'Token expirado. Faça login novamente.'], 401);
        }

        // Verifica expiração por inatividade
        $lastActivity = $token->last_activity ?? null;

        if ($lastActivity && Carbon::parse($lastActivity)->diffInMinutes(now()) > 10) {
            $token->delete(); // Invalida o token
            return response()->json(['message' => 'Sessão expirada por inatividade.'], 401);
        }

        // Atualiza o tempo de atividade
        $token->last_activity = now();
        $token->save();

        return $next($request);
    }
}
