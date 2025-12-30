<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EASYTI: Middleware para restringir acesso a funcionalidades administrativas
 * 
 * Este middleware verifica se o usuário atual pertence ao team master (Easy TI Solutions)
 * ou se é um administrador. Caso contrário, bloqueia o acesso.
 */
class EasyTiAdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Acesso não autorizado.');
        }

        $currentTeam = $user->currentTeam;
        
        if (!$currentTeam) {
            abort(403, 'Nenhum team selecionado.');
        }

        // Verifica se o team atual é o master (admin)
        if (!$currentTeam->isSuperAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }

        return $next($request);
    }
}

