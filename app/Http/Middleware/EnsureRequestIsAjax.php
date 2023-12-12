<?php

namespace App\Http\Middleware;

use App\Helpers\JsonResponder;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EnsureRequestIsAjax
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Str::contains($request->getPathInfo(), '/api/') && ! $request->ajax()) {
            Log::warning('Request cancelled by EnsureRequestIsAjax middleware');

            return JsonResponder::validationError('No XMLHttpRequest is found in X-Requested-With header', [
                'X-Requested-With' => 'only XMLHttpRequest is accepted for this header',
            ]);
        }

        return $next($request);
    }
}
