<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status_code' => 401,
                'message' => 'Les identifiants envoyés sont incorrects.'
            ], 200);
        }
        
        $token = $user->createToken('auth_token')->plainTextToken;
    
        // Récupérer le dernier token créé pour l'utilisateur
        $lastToken = $user->tokens()->latest()->first();
        $tokenId = $lastToken->id;
        
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'token_id' => $tokenId,
            'user' => $user,
            'ecole' => $user->ecole
        ], 200);
        
    }
}
