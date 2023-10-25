<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function addPersonne(Request $request) {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'telephone' => 'required'
        ]);

        $matricule = strtoupper(Str::random(9));

        $role = (int) $request->role_id;
        $classe = (int) $request->classe_id;
        $student = (int) $request->student_id;
        $ecole = (int) $request->ecole_id;

        $user = new User();
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->email = $request->email;
        $user->telephone = $request->telephone;
        $user->password = Hash::make($request->password);
        $user->matricule = $matricule;
        $user->ecole_id = $ecole;
        switch ($role) {
            case 1:
                $user->role_id = 1;
                $user->classe_id = NULL;
                $user->student_id = NULL;
                break;
            case 2:
                $user->role_id = 2;
                $user->classe_id = NULL;
                $user->student_id = NULL;
                break;
            case 3:
                $user->role_id = 3;
                $user->classe_id = $classe;
                $user->student_id = NULL;
                break;
            case 4:
                $user->role_id = 3;
                $user->classe_id = NULL;
                $user->student_id = NULL;
                break;
            default:
                $user->role_id = 5;
                $user->classe_id = NULL;
                $user->student_id = $student;
                break;
        }

        $user->remember_token = Str::random(10); 
        $user->created_at = now();
        $user->updated_at = now();
        $user->save();

        // Lorsque le mail lui sera envoye il devra le confirmer et enregistre l'email_verified et l"updated_at
        //$user->updated_at = now();

        return response([
            "message" => "Inscription reussie !", 
            "user" => $user
        ],
        Response::HTTP_CREATED
        );
    }

    public function login(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        //$credentials = $request->only('email', 'password');

        if ($user = User::where('email', $request->email)->where('password', $request->password)->get()) {
            
            //$expirationTime = Carbon::now()->addWeeks(1);
            //$token = createToken('GescoToken', ['expires_in' => $expirationTime])->plainTextToken;
           
            return response()->json($user, 200);

            /*$tokenResult = $user->createToken('token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token_>save();
            
            return response()->json(['data' => [
                'user' => Auth::user(),
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
            ]]);*/
        } else {
            return response(["message" => "Identifiants incorrects"], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function userProfile(int $id) {
        return response()->json(User::find($id), Response::HTTP_OK);
    }

    public function logout(Request $request) {
        $cookie = Cookie::forget('cookie_token');
        return response(["message" => "Deconnexion réussie"], Response::HTTP_OK)->withCookie($cookie);
    }

    public function allUsers(Request $request) {
        $users = User::all();
        return response()->json($users, 200);
    }
}
