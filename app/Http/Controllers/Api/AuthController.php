<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Lida com a tentativa de autenticação via API e retorna um token.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tenta autenticar o utilizador
        if (Auth::attempt($credentials)) {
            // Autenticação bem-sucedida. Busca o utilizador.
            $user = User::where('email', $credentials['email'])->firstOrFail();

            // Revoga todos os tokens antigos para garantir que apenas um token esteja ativo
            $user->tokens()->delete();

            // Cria um novo token para o utilizador
            $token = $user->createToken('auth-token')->plainTextToken;

            // Retorna o token como resposta JSON
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        // Falha na autenticação
        return response()->json([
            'message' => 'As credenciais fornecidas estão incorretas.'
        ], 401);
    }
}