<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;
use App\Mail\EmailVerificationSuccess;
use App\Models\User;
use Illuminate\Support\Str;

class VerifyEmailController extends Controller
{
    public function sendMail(Request $request)
    {
        $user = User::where('email', $request->email)->get();
        Mail::to($user[0])->send(new EmailVerification($user[0]));

        return response()->json([
            'message' => "Le lien de la vérification de l'email vous a été envoyé"
        ]);
    }

    public function verify(Request $request)
    {
        $user = User::where('email', $request->email)->get();
        if(!$user[0]->email_verified_at) {
            $user[0]->forceFill([
                'email_verified_at' => now(),
                'remember_token' => Str::random(10)
            ])->save();
        }

        Mail::to($user[0])->send(new EmailVerificationSuccess());

        return response()->json([
            'message' => 'Email vérifié'
        ]);
    }
}
