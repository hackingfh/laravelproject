<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Log de la requête entrante
        Log::info('Request started', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toISOString(),
            'memory_usage' => memory_get_usage(true),
            'session_id' => session()->getId()
        ]);

        try {
            $response = $next($request);
            
            // Log de la réponse
            $executionTime = microtime(true) - $startTime;
            
            Log::info('Request completed', [
                'url' => $request->fullUrl(),
                'status' => $response->getStatusCode(),
                'execution_time' => round($executionTime * 1000, 2) . 'ms',
                'memory_peak' => memory_get_peak_usage(true),
                'response_size' => strlen($response->getContent())
            ]);

            // Ajouter des headers de debug
            $response->headers->set('X-Debug-Time', round($executionTime * 1000, 2) . 'ms');
            $response->headers->set('X-Debug-Memory', round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB');

            return $response;

        } catch (\Exception $e) {
            // Log détaillé de l'exception
            Log::error('Exception caught in DebugMiddleware', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toISOString(),
                'request_data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            // En développement, retourner une page d'erreur détaillée
            if (config('app.debug')) {
                return $this->createDebugResponse($e, $request);
            }

            throw $e;
        }
    }

    private function createDebugResponse(\Exception $e, Request $request): Response
    {
        $debugData = [
            'error' => [
                'type' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace()
            ],
            'request' => [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'headers' => $request->headers->all(),
                'parameters' => $request->all(),
                'session' => session()->all()
            ],
            'server' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'environment' => config('app.env'),
                'memory_usage' => memory_get_usage(true),
                'memory_peak' => memory_get_peak_usage(true),
                'timestamp' => now()->toISOString()
            ]
        ];

        return response()->view('errors.debug', $debugData, 500);
    }
}
