<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Cache;

class ActivityByUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $expireTime = Carbon::now()->addMinute(1); // keep online for 1 min
            Cache::put('is_online' . Auth::user()->id, true, $expireTime);
            //Last Seen
            User::where('id', Auth::user()->id)->update(['is_online' => Carbon::now()]);
        }

        return $next($request);
    }
}