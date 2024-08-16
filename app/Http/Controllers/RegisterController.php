<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
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
        $user->created_at = now();
        $user->updated_at = now();
        $user->save();

        Mail::to($user->email)->send(new EmailVerification($user));

        return response()->json([
            'message' => "Profil créé ! Un email a été envoyé à l'utilisateur pour confirmer son compte"
        ], 200);
    }
}
