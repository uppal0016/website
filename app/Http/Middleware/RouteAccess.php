<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\Redirections;
use Illuminate\Support\Facades\Auth;

class RouteAccess
{

    use Redirections;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    /**
    * @developer       :   Akshay
    * @modified by     :
    * @created date    :   24-07-2018 (dd-mm-yyyy)
    * @modified date   :
    * @purpose         :   handle route access based on user roles
    * @params          :
    * @return          :   response as []
    */
    public function handle($request, Closure $next)
    {

        if(!Auth::check()){
            return redirect('/');
        }

        if(Auth::user()->status == 0 || Auth::user()->is_deleted == 1){
            Auth::logout();
            return redirect('/')->withErrors(array('message' => 'You are currently inactive. Please contact to admin.'));
        }

        $prefix = rtrim(ltrim( $request->route()->getPrefix(), '/'), '/');

        $shouldRedirect = 0;

        switch (Auth::user()->role_id) {
            case 1:
            case '1':

                if($prefix !== "admin")
                $shouldRedirect = 1;
                break;
            case 2:
            case '2':

                if($prefix !== "")
                $shouldRedirect = 1;
                break;
            case 3:
            case '3':

                if($prefix !== "pm")
                $shouldRedirect = 1;

                break;
            case 4:
            case '4':

                if($prefix !== "")
                $shouldRedirect = 1;
                break;
             case 5:
            case '5':

                if($prefix !== "")
                $shouldRedirect = 1;
                break;

            default:
                # code...
                // common 404 page
                break;
        }

        if($shouldRedirect){

            return redirect($this->pathAfterAuthentication());
            //or 404
        }

        return $next($request);
    }
}
