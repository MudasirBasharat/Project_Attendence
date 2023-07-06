<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()) {
            $lastActivity = Auth::user()->last_activity;
            $inactiveTime = Carbon::parse($lastActivity)->diffInMinutes(Carbon::now());

            if($inactiveTime > 15) {
                Auth::logout();

                return response()->json(['message' => 'Session expired due to inactivity'], 401);
            } else {
                // Update last_activity timestamp for the user
                Auth::user()->update(['last_activity' => Carbon::now()]);
            }
        }

        return $next($request);
    }
}
