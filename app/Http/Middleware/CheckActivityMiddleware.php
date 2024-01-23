<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckActivityMiddleware
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
        if(Auth::check()){
            if(Auth::user()->status == 0 || Auth::user()->is_deleted == 1){
                Auth::logout();
                return redirect('/')->withErrors(array('message' => 'You are currently inactive. Please contact to admin.'));
            }
        }
        return $next($request);
    }
}
