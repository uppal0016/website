<?php

namespace App\Traits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

trait Validate
{

  protected $rules = [];
  protected $messages = [

  ];

  public __construct(){

  }

  public validator($data, $type){

    switch ($type) {
      case 'login':

        break;

      default:
        // code...
        break;
    }

  }

}
