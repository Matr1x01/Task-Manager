<?php

namespace App\Http\Middleware;

use App\Helpers\JsonResponder;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    private int $maxAttempts = 60;
    private int $decayMinutes = 1;

    public function handle(Request $request, Closure $next)
    {
        $key = $this->resolveRequestSignature($request);
        $maxAttempts = $this->maxAttempts;
        $decayMinutes = $this->decayMinutes;

        if ($this->tooManyAttempts($key, $maxAttempts)) {
            return JsonResponder::respond(
                'Too many attempts. Please try again in ' . $decayMinutes . ' minutes.',
                Response::HTTP_TOO_MANY_REQUESTS
            );
        }

        $this->hit($key, $decayMinutes);

        return $next($request);
    }

    protected function resolveRequestSignature(Request $request): string
    {
        return sha1($request->ip().'|'.$request->path());
    }

    protected function tooManyAttempts(string $key, int $maxAttempts): bool
    {
        return Cache::get($key, 0) >= $maxAttempts;
    }

    protected function hit(string $key, int $decayMinutes): void
    {
        $hits = (int) Cache::get($key, 0);
        Cache::put($key, $hits + 1, now()->addMinutes($decayMinutes));
    }
}
