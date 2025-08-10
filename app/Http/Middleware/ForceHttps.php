<?php

// 🔒 Middleware para forçar HTTPS em produção
// Adicione este arquivo em app/Http/Middleware/ForceHttps.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    public function handle(Request $request, Closure $next)
    {
        // Forçar HTTPS em produção
        if (app()->environment('production') && !$request->isSecure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // Configurar headers para HTTPS
        if (app()->environment('production')) {
            \URL::forceScheme('https');
        }

        return $next($request);
    }
}
