<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Reparateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function InscrisUtilisateur(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'numero' => 'required|string|max:15',
            'quartier' => 'required|string|max:255',
            'statut' => 'required|string|in:client,reparateur',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $utilisateur = new User;
        
        $utilisateur->nom = $request->nom;
        $utilisateur->email = $request->email;
        $utilisateur->numero = $request->numero;
        $utilisateur->quartier = $request->quartier;
        $utilisateur->statut = $request->statut;
        $utilisateur->password = bcrypt($request->password);

        $utilisateur->save();
    
        if ($utilisateur->statut === 'client') {
            $client = Client::create([
                'nom' => $validatedData['nom'],
                'email' => $validatedData['email'],
                'numero' => $validatedData['numero'],
                'quartier' => $validatedData['quartier'],
                'password' => Hash::make($validatedData['password']),
                'user_id' => $utilisateur->id,
            ]);
            return response()->json(['message' => 'Client inscrit avec succès!', 'client' => $client], 201);
        } elseif ($utilisateur->statut === 'reparateur') {
            $reparateur = Reparateur::create([
                'nom' => $validatedData['nom'],
                'email' => $validatedData['email'],
                'numero' => $validatedData['numero'],
                'quartier' => $validatedData['quartier'],
                'password' => Hash::make($validatedData['password']),
                'user_id' => $utilisateur->id,
            ]);
            return response()->json(['message' => 'Réparateur inscrit avec succès!', 'reparateur' => $reparateur], 201);
        } else {
            return response()->json(['error' => 'Statut de l\'utilisateur non valide'], 400);
        }
    }


    public function connexion(Request $request)
    {
    try {
        $request->validate([
        'email' => 'email|required',
        'password' => 'required'
        ]);
        
        $credentials = request(['email', 'password']);
        
        if (!Auth::attempt($credentials)) {
        return response()->json([
            'status_code' => 500,
            'message' => 'non authoriser'
        ]);
        }
        
        $user = User::where('email', $request->email)->first();
        
        $tokenResult = $user->createToken('authToken')->plainTextToken;
        
        return response()->json([
        'status_code' => 200,
        'access_token' => $tokenResult,
        'token_type' => 'Bearer',
        'message'  => 'connexion reussir'
        ]);
        
    } catch (Exception $error) {
        return response()->json([
        'status_code' => 500,
        'message' => 'Error in Login',
        'error' => $error,
        ]);
    }
    } 

}
