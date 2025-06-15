<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui é onde você pode registrar as rotas de API para sua aplicação. Essas
| rotas são carregadas pelo RouteServiceProvider dentro de um grupo que
| recebe o middleware "api". Aproveite para construir sua API!
|
*/

Route::post('/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    $tokenResult = $user->createToken('token-api');
    $token = $tokenResult->plainTextToken;

    // Atualiza expiração e atividade
    $tokenModel = $tokenResult->accessToken;
    $tokenModel->expires_at = Carbon::now()->addMinutes(60);
    $tokenModel->last_activity = Carbon::now();
    $tokenModel->save();

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
});

Route::middleware(['auth:sanctum', 'check.inactivity'])->post('/refresh', function (Request $request) {
    $user = $request->user();

    if (!$user) {
        return response()->json(['message' => 'Usuário não autenticado. Faça login novamente.'], 401);
    }

    $token = $user->currentAccessToken();

    if (!$token) {
        return response()->json(['message' => 'Token de acesso não encontrado. Faça login novamente.'], 401);
    }

    if ($token->expires_at && Carbon::parse($token->expires_at)->isPast()) {
        $token->delete(); // Opcional: remove token expirado
        return response()->json(['message' => 'Token expirado. Faça login novamente.'], 401);
    }

    // Renova o token (expiração e atividade)
    $token->expires_at = now()->addMinutes(60);
    $token->last_activity = now();
    $token->save();

    return response()->json(['message' => 'Token renovado com sucesso.']);
});

Route::middleware(['auth:sanctum', 'check.inactivity'])->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logout feito com sucesso']);
});

Route::middleware(['auth:sanctum', 'check.inactivity'])->apiResource('user', UserController::class);
