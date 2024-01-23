<?php

namespace App\Http\Middleware;

use App\Reference;
use Closure;
use App\Traits\Redirections;
use Illuminate\Support\Facades\Auth;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use DB;
class RedirectIfAuthenticated
{
    use Redirections;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
 
       if(!empty($request->service) &&  Auth::guard($guard)->check()){
        $user = User::where(['email'=>Auth::user()->email,'interviewPanelStatus'=>1])->first();
        if(!empty($user)){
                     
              $time = 480;            
              JWTAuth::factory()->setTTL($time);           
              $token = JWTAuth::fromUser($user);                   
              $user  = User::findOrFail($user->id);        
              $user->update(['other_services'=>$token]);
              $platformUrl = DB::table('platform')->where('platform_services','=',$request->service)->first();
              if($request->service == 'interview_panel'){  
              return redirect(''.$platformUrl->platform_url.'/interviewlist?token='.$token);  
              }else if($request->service == 'local_interview_panel'){               
               return redirect(''.$platformUrl->platform_url.'/interviewlist?token='.$token); 
              } 

                $id = $request->query('id');
                $reference = Reference::find($id);
              if($request->service == 'reference_candidate'){  
                  return redirect('' . $platformUrl->platform_url . '/interviewlist?token=' . $token . '&reference_candidate_id=' . $id)->with('json_data', json_encode($reference));
                  }else if($request->service == 'local_reference_candidate'){    
                    return redirect('' . $platformUrl->platform_url . '/interviewlist?token=' . $token . '&reference_candidate_id=' . $id)->with('json_data', json_encode($reference));
                  } 
              return redirect($this->pathAfterAuthentication()); 
        }
        return redirect($this->pathAfterAuthentication());
      }
        if (Auth::guard($guard)->check()) {
            if(Auth::user()->status == 0 || Auth::user()->is_deleted == 1){
                Auth::logout();
                return redirect('/')->withErrors(array('message' => 'You are currently inactive. Please contact to admin.'));
            }
            return redirect($this->pathAfterAuthentication());
        }

        return $next($request);
  
}
}
