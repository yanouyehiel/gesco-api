<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $expirationTime = Carbon::now()->addWeeks(1);
            $token = $user->createToken('GescoToken', ['expires_in' => $expirationTime])->plainTextToken;
           
            return response()->json($token, 200);

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

    public function deleteUser(int $id)
    {
        $user = User::find($id);
        $user->delete();

        return response()->json('Suppression réussie !', 200);
    }
}
