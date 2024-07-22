<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $user = DB::table('users')
            ->join('ecoles', 'users.ecole_id', '=', 'ecoles.id')
            ->select('users.*', 'ecoles.bloque as bloque')
            ->where('users.email', $request->email)
            ->where('users.password', $request->password)
            ->get();

        if (count($user) == 1)            
            return response()->json($user[0], 200);
        else
            return response(["message" => "Identifiants incorrects"], Response::HTTP_UNAUTHORIZED);
    }

    /*public function login(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $tokenResult = $user->createToken('token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();
            
            return response()->json(['data' => [
                'user' => Auth::user(),
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
            ]]);
        } else {
            return response(["message" => "Identifiants incorrects"], Response::HTTP_UNAUTHORIZED);
        }
    }*/

    public function deleteUser(int $id)
    {
        $user = User::find($id);
        $user->delete();

        return response()->json('Suppression réussie !', 200);
    }

    public function updateUser(Request $req)
    {
        $user = User::find((int) $req->id);
        $user->email = $req->email;
        $user->telephone = $req->tel;
        $user->update();

        return response([
            'message' => 'Le profil a bien été mis à jour',
            'user' => $user
        ])->json();
    }
}
