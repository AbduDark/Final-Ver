
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ApiSecurityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // فحص Rate Limiting
        $key = $request->ip();
        $maxAttempts = 100; // 100 طلب في الدقيقة
        $decayMinutes = 1;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'error' => 'تم تجاوز الحد المسموح من الطلبات'
            ], 429);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        // إضافة CSRF token للحماية
        if (!$request->hasValidSignature() && $request->isMethod('post')) {
            if (!$request->hasValidSignature()) {
                // التحقق من CSRF token
                $token = $request->header('X-CSRF-TOKEN') ?: $request->input('_token');
                if (!hash_equals(csrf_token(), $token)) {
                    return response()->json(['error' => 'غير مصرح'], 403);
                }
            }
        }

        return $next($request);
    }
}
