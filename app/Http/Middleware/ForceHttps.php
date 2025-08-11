<?php

// 🔒 Middleware para forçar HTTPS em produção
// Modificado para Railway - proxy handle HTTPS externamente

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    public function handle(Request $request, Closure $next)
    {
        // No Railway, o proxy externo lida com HTTPS
        // Apenas definir URL scheme, sem forçar redirect
        if (app()->environment('production')) {
            \URL::forceScheme('https');
            
            // Definir headers para indicar HTTPS
            $request->server->set('HTTPS', 'on');
            $request->server->set('SERVER_PORT', 443);
        }

        return $next($request);
    }
}
