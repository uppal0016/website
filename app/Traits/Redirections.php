<?php

namespace App\Traits;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

trait Redirections
{

  protected static $path;

  public function __construct(){
    self::$path = '/';
  }


  /**
  * @developer       :   Akshay
  * @modified by     :
  * @created date    :   06-07-2018 (dd-mm-yyyy)
  * @modified date   :
  * @purpose         :   to get default path to redirect after authentication
  * @params          :
  * @return          :   response as []
  */
  public function pathAfterAuthentication(){
  
    if(!Auth::check()){      
        return $path;
    }
    // $txt = session()->get('qr_code_url');
    // if($txt){
    //   $str = preg_replace('/\W\w+\s*(\W*)$/', '$1', $txt);
    //   $qrcode_url  = substr( $str, strrpos( $str, '/' )+1);   
    // }    
    switch (Auth::user()->role_id) {

      case 1:        
       $path = '/admin/dashboard'; 
        // if($qrcode_url =='qr_code'){
        // $path  = session()->get('qr_code_url'); 
        // }      
        break;
      case 2:

        $path = '/admin/dashboard';
        break;
      case 3:

        $path = 'dashboard';
        break;
      case 4:

        $path = 'dashboard';
        break;

        case 5:

        $path = 'dashboard';
        break;

      default:

        // code... 404
        $path = '/nowhere';
        break;
    }

    return $path;
  }


}
