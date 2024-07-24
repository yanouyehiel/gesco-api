<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Ecole;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;

class AuthController extends Controller
{
    public function addPersonne(Request $request) {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|string|unique:users',
            'password' => 'required|string|min:8',
            'telephone' => 'required'
        ]);

        $matricule = strtoupper(Str::random(9));

        $user = new User();
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->telephone = $request->telephone;
        $user->password = Hash::make($request->password);
        $user->matricule = $matricule;
        $user->ecole_id = (int) $request->ecole_id;
        $user->role_id = (int) $request->role_id;
        $user->remember_token = Str::random(10); 
        $user->created_at = now();
        $user->updated_at = now();
        $user->save();

        Mail::to($user)->send(new EmailVerification($user));

        return response()->json([
            'message' => "Nouvel utilisateur créé avec succès !",
            'user' => $user
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function userProfile(int $id) {
        return response()->json(User::find($id), Response::HTTP_OK);
    }

    public function allUsers(Request $request) {
        $users = User::all();
        return response()->json($users, 200);
    }

    public function addPersonneFromMobile(Request $request) {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|string|unique:users',
            'password' => 'required|string|min:8',
            'telephone' => 'required'
        ]);

        $matricule = strtoupper(Str::random(9));
        $ecole = Ecole::where('matricule', (int) $request->matricule)->get();

        if (count($ecole) > 0) {
            $user = new User();
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->email = $request->email;
            $user->telephone = $request->telephone;
            $user->password = Hash::make($request->password);
            $user->matricule = $matricule;
            $user->ecole_id = (int) $ecole[0]->id;
            $user->role_id = (int) $request->role_id;
            $user->remember_token = Str::random(10); 
            $user->created_at = now();
            $user->updated_at = now();
            $user->save();

            Mail::to($user)->send(new EmailVerification($user));

            $token = $user->createToken('auth_token')->plainTextToken;
    
            // Récupérer le dernier token créé pour l'utilisateur
            $lastToken = $user->tokens()->latest()->first();
            $tokenId = $lastToken->id;
            
            return response()->json([
                'access_token' => $token,
                'message' => "Nouveau profil créé avec succès !",
                'token_type' => 'Bearer',
                'token_id' => $tokenId,
                'user' => $user,
                'ecole' => $user->ecole
            ], 200);
        } else {
            return response()->json([
                'message' => "L'école avec ce matricule n'existe pas."
            ], 500);
        }
        
    }
}
