<?php

namespace App\Http\Middleware;

use Closure;

class AcceptJson
{
    public function handle($request, Closure $next)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader != 'application/json') {
            return response()->json([], 400);
        }

        return $next($request);
    }
}
