<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        if (!in_array($user->type, $roles)) {
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        return $next($request);n $next($request);
    }
}
