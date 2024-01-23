<?php

namespace App\Http\Controllers;

use App\Department;
use App\Designation;
use App\Technology;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $response;

    public function __construct(){
    	$this->response = self::getResponse();
    }

    public static function getResponse(){
        return array('message' => '', 'success' => false, 'data' => [], 'status' => 400);
    }


}
