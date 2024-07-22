<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        auth()->user()->tokens()->where('id', $request->token_id)->delete();
        
        return response()->json([
            'message' => "Utilisateur déconnecté sur cet appareil"
        ], 200);
    }
}
