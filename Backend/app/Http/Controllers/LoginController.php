<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Authentifie un utilisateur et génère un token de session.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Valide les données d'entrée
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Tente de connecter l'utilisateur avec les informations d'identification fournies
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status_code' => 401,
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
        }

        // Récupère l'utilisateur authentifié
        $user = Auth::user();

        // Crée un token pour l'utilisateur
        $token = $user->createToken('authToken')->plainTextToken;

        // Retourne la réponse JSON avec le token
        return response()->json([
            'status_code' => 200,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
