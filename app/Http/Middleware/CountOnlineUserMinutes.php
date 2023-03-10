<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Support\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;


class CountOnlineUserMinutes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if(Auth::check()){
            $auth = User::find(auth()->id()); // get current user
            $last_login_time = Carbon::parse($auth->last_login_at); // fetch user last login time
            if(!is_null($auth->last_login_at)){ // cheching "is last_login_time column not null"
                $minutes = (Carbon::now()->diffInSeconds($last_login_time) / 60); // get spended minutes count
                $auth->spended_minutes_on_site += $minutes; // add minutes count into the existing spended minutes count
            }
            $auth->last_login_at = Carbon::now(); // update last login at field to current time
            $auth->save();
        }

        return $next($request);
    }
}
