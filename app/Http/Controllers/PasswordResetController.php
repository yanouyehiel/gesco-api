<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LinkEmailRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\ResetPasswordLink;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\ResetPasswordSuccess;
use App\Models\User;

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
}
