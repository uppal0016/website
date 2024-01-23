<?php

namespace App\Traits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

trait Sanitize
{


  public function __construct(){

  }

  public function sanitize($string){
    /*----- LONG CODE ------*/
    // $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    // $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    // return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    /*----- LONG CODE ------*/

    // return preg_replace('/-+/','-',preg_replace('/[^A-Za-z0-9\-]/','',str_replace(' ','-',$string))); // SHORT ONE 
    return str_replace('=', ' ', preg_replace('/[^A-Za-z0-9\-=]/', '' , str_replace(' ','=',$string)));
  }

}
