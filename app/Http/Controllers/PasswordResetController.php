<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CodePhone;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordLink;
use App\Mail\ResetPasswordSuccess;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\LinkEmailRequest;
use App\Mail\Mail\SendCodeResetPassword;
use App\Http\Requests\ResetPasswordRequest;

class PasswordResetController extends Controller
{
    /**
     * Handle the incoming request.
     */

    public function sendResetLinkEmail(LinkEmailRequest $request)
    {
        $expires = now()->addMinutes(30)->getTimestamp();
        $signature = hash_hmac('sha256', $request->email . '|' . $expires, config('app.key'));

        $url = "https://gesco-app.com/#/auth/password/reset/" . $request->email . '/' . $expires . '/' . $signature;

        Mail::to($request->email)->send(new ResetPasswordLink($url));

        return response()->json([
            'message' => 'Le lien pour regénérer un nouveau mot de passe a été envoyé par mail'
        ]);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $user = User::whereEmail($request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => "L'utilisateur n'existe pas"
            ], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();
        
        Mail::to($user->email)->send(new ResetPasswordSuccess($user));

        return response()->json([
            'message' => "Mot de passe modifié avec succès !"
        ], 200);
    }

    public function sendCodeResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => "L'utilisateur n'existe pas"], 404);
        }

        $phone = $user->phone; // Assurez-vous que le champ phone existe dans la table users

        // Générer un code à 6 chiffres aléatoire
        $code = random_int(100000, 999999);

        // Stocker ou mettre à jour le code dans la table code_phones
        CodePhone::updateOrCreate(
            ['user_id' => $user->id],
            ['phone' => $phone, 'code' => $code]
        );

        // Envoyer le code par mail
        Mail::to($user->email)->send(new SendCodeResetPassword($code));

        return response()->json(['message' => 'Un code de vérification a été envoyé par mail']);
    }

    public function resetPasswordWithCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $codePhone = CodePhone::where('code', $request->code)->first();
        if (!$codePhone) {
            return response()->json(['message' => 'Code invalide'], 400);
        }

        $user = $codePhone->user;

        // Vous pouvez ajouter ici une vérification d'expiration du code si vous stockez un timestamp

        // Réinitialiser le mot de passe
        $user->password = bcrypt($request->password);
        $user->save();

        // Supprimer le code après usage
        $codePhone->delete();

        // Envoyer un mail de confirmation
        Mail::to($user->email)->send(new ResetPasswordSuccess($user));

        return response()->json(['message' => 'Mot de passe modifié avec succès']);
    }

}
