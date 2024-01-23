<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->name('api.')->middleware('auth:api')->group(function(){

  Route::resource('/dsrs', 'DSRController');

  Route::post('/user/get_time_estimates','UserController@getTimeEstimates');
});
Route::get('verifytoken/{token}', 'Api\UserController@verifyToken');
Route::post('forgot-password', 'Api\UserController@forgot_password');
Route::post('Login','Api\UserController@loginApi');
Route::group(['middleware' => ['jwt.verify']], function() {
Route::middleware(['adminroute.access', 'check_activity'])->group(function(){
     Route::get('Employees', 'Api\UserController@AllEmployees');
     Route::get('getHr', 'Api\UserController@getHr');     
     Route::resource('/designation', 'Api\DesignationController')->only(['index','store','update','destroy']);         
     Route::get('logout', 'Api\UserController@logout');
});
 });

Route::post('/rapper_candidate', 'ReferenceController@rapper_candidate')->name('reference.rapper_candidate'); // getting data from Talenthire

Route::post('/biometric_data', 'BiometricController@biometric_data')->name('biometric.biometric_data');
