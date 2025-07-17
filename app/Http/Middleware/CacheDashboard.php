<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cache apenas para GET requests do dashboard
        if ($request->method() === 'GET' && $request->is('dashboard*')) {
            $cacheKey = 'dashboard_response_' . auth()->id();
            
            // Verificar se temos resposta em cache (30 segundos)
            if (Cache::has($cacheKey)) {
                $cachedResponse = Cache::get($cacheKey);
                return response($cachedResponse['content'])
                    ->header('Content-Type', $cachedResponse['content_type'])
                    ->header('X-Cache', 'HIT');
            }
        }

        $response = $next($request);

        // Cache a resposta se for dashboard
        if ($request->method() === 'GET' && $request->is('dashboard*') && $response->isSuccessful()) {
            $cacheKey = 'dashboard_response_' . auth()->id();
            Cache::put($cacheKey, [
                'content' => $response->getContent(),
                'content_type' => $response->headers->get('Content-Type', 'text/html')
            ], 30); // 30 segundos
            
            $response->header('X-Cache', 'MISS');
        }

        return $response;
    }
}
