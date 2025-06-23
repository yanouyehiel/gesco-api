<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Ecole;

class CheckEcole
{
    public Ecole $ecole;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        $user = auth()->user();
        if ($user && $user->ecole) {
            $this->ecole = $user->ecole;
            
            if ($this->ecole->bloque == 1) {
                return response()->json([
                    'message' => "Désolé, votre école est momentanément bloquée.",
                    'status_code' => 401,
                ], 401);
            }
        }
        
        return $next($request);
    }
}
