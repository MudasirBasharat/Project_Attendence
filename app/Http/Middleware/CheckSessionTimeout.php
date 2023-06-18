<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
class CheckSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->exists('expires_at')) {
            $expiresAt = Carbon::parse($request->session()->get('expires_at'));
            if (Carbon::now()->gt($expiresAt)) {
                auth()->logout();
                $request->session()->flush();
                return redirect('/login')->with('message', 'Your session has expired. Please login again.');
            }
            // Extend the session expiration time
            $request->session()->put('expires_at', now()->addHours(24));
        }

        return $next($request);
    }
    public function tokensExpireIn()
{
    return now()->addHours(24);
}

}
