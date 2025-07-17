<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class LoginRateLimit
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        $key = $this->resolveRequestSignature($request);
        $maxAttempts = 5; // Máximo 5 tentativas
        $decayMinutes = 1; // Por minuto

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $this->limiter->availableIn($key);
            
            // Log da tentativa de força bruta
            \Log::warning('Rate limit exceeded for login', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'email' => $request->input('email'),
                'retry_after' => $seconds
            ]);
            
            return response()->json([
                'message' => 'Muitas tentativas de login. Tente novamente em ' . $seconds . ' segundos.',
                'retry_after' => $seconds
            ], 429);
        }

        $response = $next($request);

        // Se o login falhou, incrementar contador
        if ($response->getStatusCode() === 422 || 
            ($response instanceof \Illuminate\Http\RedirectResponse && 
             $response->getSession()->hasOldInput('email'))) {
            
            $this->limiter->hit($key, $decayMinutes * 60);
            
            // Log da tentativa de login falhada
            \Log::info('Failed login attempt', [
                'ip' => $request->ip(),
                'email' => $request->input('email'),
                'attempts' => $this->limiter->attempts($key)
            ]);
        } else {
            // Login bem-sucedido, limpar contador
            $this->limiter->clear($key);
            
            // Log do login bem-sucedido
            \Log::info('Successful login', [
                'ip' => $request->ip(),
                'email' => $request->input('email')
            ]);
        }

        return $response;
    }

    /**
     * Resolve request signature.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        return sha1(
            $request->ip() . '|' . 
            $request->input('email', '') . '|' . 
            $request->userAgent()
        );
    }
}
