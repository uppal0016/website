<?php

namespace App\Http\Middleware;
use Closure;
use Auth;
class CheckPermission
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
      if(\Illuminate\Support\Facades\Auth::user()->status == 0 || \Illuminate\Support\Facades\Auth::user()->is_deleted == 1 ){
          \Illuminate\Support\Facades\Auth::logout();

          return redirect('/')->withErrors(array('message' => 'You are currently inactive. Please contact to admin.'));
      }
    if(Auth::user()->permission_id != '')
    {
      $permissionRole = explode(',',Auth::user()->permission_id);
      if(!in_array('1',$permissionRole))
      {
        return response()->view('employee/unauthorized');
      }
      if(!in_array('2',$permissionRole))
      {
        return response()->view('employee/unauthorized');
      }
    }
    return $next($request);
  }
}
