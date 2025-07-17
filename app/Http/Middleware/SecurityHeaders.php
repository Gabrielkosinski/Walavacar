<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Headers de segurança para proteger contra ataques comuns
        $response->headers->set('X-Frame-Options', 'DENY'); // Previne clickjacking
        $response->headers->set('X-Content-Type-Options', 'nosniff'); // Previne MIME sniffing
        $response->headers->set('X-XSS-Protection', '1; mode=block'); // Proteção XSS
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin'); // Controla referrer
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()'); // Restringe APIs
        
        // Content Security Policy - ajustar conforme necessário
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; " .
               "font-src 'self' https://fonts.gstatic.com; " .
               "img-src 'self' data: https:; " .
               "connect-src 'self' https://wa.me; " .
               "frame-ancestors 'none';";
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        return $response;
    }
}
