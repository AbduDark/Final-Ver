<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $storeId = $request->route('store') ?: $request->input('store_id');
        
        // السوبر أدمن يمكنه الوصول لجميع المتاجر
        if ($user->type === 'super_admin') {
            return $next($request);
        }
        
        // التأكد من أن المستخدم يتبع لنفس المتجر
        if ($user->store_id != $storeId) {
            abort(403, 'لا يمكنك الوصول لبيانات متجر آخر');
        }

        return $next($request);
    }
}
