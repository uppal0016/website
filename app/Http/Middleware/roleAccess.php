<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\Redirections;
use Illuminate\Support\Facades\Auth;

class roleAccess
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
    if(Auth::user()->role_id == 1 || Auth::user()->role_id == 3 || Auth::user()->role_id == 2 ){
      return $next($request);
    }
    return redirect('/dashboard');
  }
}
