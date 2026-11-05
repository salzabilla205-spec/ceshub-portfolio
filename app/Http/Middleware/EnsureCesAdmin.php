<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class EnsureCesAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) return redirect('/admin/login');
        abort_unless(auth()->user()->is_admin, 403);
        return $next($request);
    }
}
