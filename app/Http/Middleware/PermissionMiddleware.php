<?php
// app/Http/Middleware/PermissionMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $routeName = $request->route()->getName();

        // Allow access if no route name specified
        if (!$routeName) {
            return $next($request);
        }

        // Allow access if user has permission
        if ($user && $user->hasPermissionForRoute($routeName)) {
            return $next($request);
        }

        abort(403, 'Unauthorized: You do not have permission to access this route.'.$routeName);
    }
}
