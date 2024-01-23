<?php
session()->put('qr_code_url',url()->current());
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('auth.login');
// });

// Route::get('storage/{filename}', function($filename){
//   dd(storage_path());
// });
Route::get('/test',function(){
  phpinfo();
});
Route::get('/test/email','TestController@email');
Route::get('/testEmail', 'AttendanceController@testing');
Route::get('/', function(){ 
  return redirect('/login');
});

Auth::routes();
use RahulHaque\Filepond\Http\Controllers\FilepondController;

Route::post('/filepond', [FilepondController::class, 'process'])->name('filepond.process');
Route::get('logout', 'Auth\LoginController@logout');
Route::get('logoutinterviewpanel', 'Admin\UserController@logoutinterviewpanel');
Route::get('logoutReferencePanel', 'ReferenceController@logoutReferencePanel');
Route::post('forget-password', 'Auth\ForgotPasswordController@submitForgetPasswordForm');
Route::post('reset.password.post', 'Auth\ForgotPasswordController@submitResetPasswordForm');
Route::get('/inventory_item/show/{id}','QrInventoryItemController@show');
Route::get('/qr_code/check/{id}','QrInventoryItemController@check');

/*-------------------------- Admin Routes Start---------------------------*/
Route::prefix('admin')->namespace('Admin')->name('admin.')->middleware(['adminroute.access', 'check_activity'])->group(function(){

  Route::resource('/dashboard', 'DashboardController');
  // Route::resource('/users', 'UserController');
  // Route::post('/users', 'UserController@index');
  // Route::get('/user/create','UserController@roleList');
  // Route::post('/user/create','UserController@store');
  // Route::post('/user/get_time_estimates','UserController@getTimeEstimates');
  // Route::get('/destroy/{id}','UserController@destroy');
  // Route::post('/assign','UserController@assign_project');
  
  /*------------------------------Inventory-------------------------------------*/
  Route::resource('/inventory','InventoryController');
  Route::any('/inventory-filter',array('uses' => 'InventoryController@inventory_filter'));
  // Categories
  Route::resource('/category','CategoryController');
  Route::post('/category','CategoryController@index');
  Route::post('/category/create','CategoryController@store');
  Route::any('/category-search','CategoryController@categorySearch');
  Route::any('/change_category_status/{id}','CategoryController@change_category_status');
  // Vendor
  Route::resource('/vendor','VendorController');
  Route::post('/vendor','VendorController@index');
  Route::post('/vendor/create','VendorController@store');
  Route::any('/vendor-search','VendorController@vendorSearch');
  Route::any('/change_vendor_status/{id}','VendorController@change_vendor_status');

  // Inventory Items
  Route::resource('/inventory_item','InventoryItemController');
  Route::get('/inventory_item','InventoryItemController@index')->name('inventory_item');  
  Route::get('/view_qr_code','InventoryItemController@view_qr_code');
  Route::get('/remove_inventoryDetails','InventoryItemController@remove_inventory_details');
  Route::get('/inventory_details','InventoryItemController@inventory_details');
  Route::get('/scrap_details','InventoryItemController@scrap_details');
  Route::get('/inventory_item/destroy/{id}','InventoryController@destroy');
  Route::any('/inventoryItem-search','InventoryItemController@inventoryItemSearch');
  Route::post('/get_item_details',array('uses' => 'InventoryItemController@get_item_details'));
  Route::post('/change_item_status',array('uses' => 'InventoryItemController@change_item_status'));
  //Qrcode
  Route::resource('/qr_code','QrCodeController'); 
  Route::post('/qrcode/genrate',array('uses' => 'QrCodeController@store')); 
  Route::post('/qr_code','QrCodeController@index');
  Route::get('/unassign_destroy','QrCodeController@destroy');  
  Route::post('/assigned_item',array('uses' => 'QrCodeController@assigned_item'));

  // assigned stock
  Route::any('/assigned_stock',array('uses' => 'InventoryItemController@assigned_stock'));
  Route::any('/get_parameters',array('uses' => 'InventoryItemController@get_parameters'));

  /*------------------------Department Routes-----------------*/

  Route::resource('/department', 'DepartmentController');
  Route::post('/department','DepartmentController@index');
  Route::post('/department/create','DepartmentController@store');
  Route::get('/department_status/{id}','DepartmentController@status');

  /*------------------------Designations Routes-----------------*/

  Route::resource('/designations', 'DesignationController');
  Route::post('/designations','DesignationController@index');
  Route::post('/designations/create','DesignationController@store');
  Route::get('/designation_status/{id}','DesignationController@status');

  /*------------------------Birthday Cards-----------------------------*/
  Route::resource('/birthday', 'BirthdayController');
  Route::post('/birthday', 'BirthdayController@index');
  Route::post('/birthday/store', 'BirthdayController@store');
  Route::get('/birthday/status/{id}/{status}', 'BirthdayController@changeStatus');
  Route::get('/birthday/destroy/{id}', 'BirthdayController@destroy');

  /*----------------------------Festival Cards--------------------------*/
  Route::resource('/festival', 'FestivalController');
  Route::post('/festival', 'FestivalController@index');
  Route::post('/festival/store', 'FestivalController@store');
  Route::get('/festival/status/{id}/{status}', 'FestivalController@changeStatus');
  Route::get('/festival/destroy/{id}', 'FestivalController@destroy');

  /*----------------------------Holidays--------------------------*/
  Route::resource('/holiday', 'HolidayController');
  Route::post('/holiday', 'HolidayController@index');
  Route::get('/holiday-search', 'HolidayController@search');
  Route::post('/holiday/store', 'HolidayController@store');
  Route::get('/holiday/status/{id}/{status}', 'HolidayController@changeStatus');
  Route::get('/holiday/destroy/{id}', 'HolidayController@destroy');

});
/*------------------------------Admin Routes End-------------------------------------*/

/*-------------------------- Employee Routes ---------------------------*/
Route::namespace('Employee')->name('emp.')->middleware(['auth','check_activity'])->group(function(){
  //->middleware(['route.access'])

  Route::resource('/dashboard', 'DashboardController');
  Route::resource('/dsrs', 'DSRController');
  Route::get('/add_dsr','DSRController@add_Dsrs');  
  Route::post('/add_dsr','DSRController@add_Dsrs'); 
  Route::get('/dsrdetail','DSRController@dsr');
  Route::get('/sent_dsr','DSRController@Dsrsent');
  Route::get('/team_dsr','DSRController@teamsDsr');
  Route::post('/team_dsr','DSRController@teamsDsr');
  Route::get('/user_dsrs/{id}','DSRController@showdsr'); 
  Route::get('/get_dsr_details/{id}','DSRController@DsrDetails');
  Route::get('/dsr_s/search_dsrs','DSRController@getDsrs');
  Route::post('/addOneHourDsr','DSRController@storeOneHourDsr');
  Route::resource('comments', '\App\Http\Controllers\CommentController');
  Route::resource('notification', '\App\Http\Controllers\NotificationController');
  Route::get('/change_password','\App\Http\Controllers\UserController@change_password_view');
  Route::post('/change_password','\App\Http\Controllers\UserController@change_password');
  Route::post('/change-profile-picture','\App\Http\Controllers\UserController@changeProfilePicture');
  Route::get('/remove-profile-picture', '\App\Http\Controllers\UserController@removeProfilePicture');
  // Route::group(['middleware' => 'CheckPermission.access'], function()
  // {
  //   /*------------------------------HRM-------------------------------------*/
  //   Route::resource('/users', 'UserController');
  //   Route::post('/users', 'UserController@index');
  //   Route::get('/user/create','UserController@roleList');
  //   Route::post('/user/create','UserController@store');
  //   Route::post('/user/get_time_estimates','UserController@getTimeEstimates');
  //   Route::get('/destroy/{id}','UserController@destroy');
  //   Route::post('/assign','UserController@assign_project');
  //   Route::get('/summary','DSRController@summarylist');
  // });

  Route::group(['middleware' => 'CheckPermission.access'], function()
  {
    /*------------------------------Inventory-------------------------------------*/
    Route::resource('/inventory','InventoryController');
    Route::any('/inventory-filter',array('uses' => 'InventoryController@inventory_filter'));
    // Categories
    Route::resource('/category','CategoryController');
    Route::any('/category-search','CategoryController@categorySearch');
    Route::any('/change_category_status/{id}','CategoryController@change_category_status');
    // Vendor
    Route::resource('/vendor','VendorController');
    Route::any('/vendor-search','VendorController@vendorSearch');
    Route::any('/change_vendor_status/{id}','VendorController@change_vendor_status');

    // Inventory Items
    Route::resource('/inventory_item','InventoryItemController');
    Route::any('/inventoryItem-search','InventoryItemController@inventoryItemSearch');
    Route::post('/get_item_details',array('uses' => 'InventoryItemController@get_item_details'));
    Route::post('/change_item_status',array('uses' => 'InventoryItemController@change_item_status'));
    // assigned stock
    Route::any('/assigned_stock',array('uses' => 'InventoryItemController@assigned_stock'));
    Route::any('/get_parameters',array('uses' => 'InventoryItemController@get_parameters'));

    
    /*-------------------------------Inventory--------------------*/
  });
});

Route::prefix('common')->middleware(['auth', 'check_activity'])->name('common.')->group(function(){
  Route::resource('notification', 'NotificationController');
  Route::get('/dsrdetail','Employee\DSRController@dsr')->name('emp.dsrdetail');
  Route::get('/user_dsrs/{id}','Management\DSRController@showdsrs')->name('management.user_dsrs');
  Route::get('/user_dsr/{id}','PM\DsrController@showdsr')->name('pm.user_dsrs');
  Route::post('/time_in' , 'AttendanceController@timeInAction')->name('attendance.timein');
  Route::post('/time_out' , 'AttendanceController@timeOutAction')->name('attendance.timeout');

});
Route::prefix('admin')->middleware(['auth', 'route.access'])->namespace('Admin')->name('admin.')->group(function(){
    /*------------------------Project Routes-----------------*/
  Route::resource('/projects', 'ProjectController');
  Route::post('/projects','ProjectController@index');
  Route::post('/projects/{id}','ProjectController@update');
  Route::get('/create_project','ProjectController@view');
  Route::post('/create_project/create','ProjectController@store');
  Route::get('/project_status/{id}','ProjectController@status');
  Route::get('/projects/destroy/{id}', 'ProjectController@destroy');
  Route::get('/project/get_assigned_employees/{pid}', 'ProjectController@getAssignedEmployees');
  Route::get('/project/get_assigned_employees/search/autocomplete','ProjectController@autocomplete');
  Route::post('/project/update_assigned_employees', 'ProjectController@updateAssignedEmployees');
  Route::get('/summary','DSRController@summarylist');
  Route::get('/project/assigned_all_employees/{pid}', 'ProjectController@assignToAllEmployees');
  Route::get('/project/un_assigned_all_employees/{pid}', 'ProjectController@unAssignToAllEmployees');

  });

Route::prefix('admin')->middleware(['auth', 'check_activity'])->namespace('Admin')->name('admin.')->group(function(){



  /*------------------------Dsr Routes-----------------*/
  Route::resource('/dsr', 'DSRController');
  Route::post('/dsr','DSRController@index');
  Route::get('/dsr_s/search_dsrs','DSRController@getDsrs');
  Route::get('/view','DSRController@view');
  Route::get('/user','DSRController@userview');
  Route::get('/dsr_list/{id}','DSRController@dsrdetail');
  Route::get('/user_dsrs/{id}','DSRController@showdsr')->name('user_dsrs');
  Route::get('/get_dsr_details/{id}','DSRController@getDsrDetails');
  Route::post('/DsrStatusUpdate','DSRController@DsrStatusUpdate');
  Route::get('search/autocomplete','DSRController@autocomplete');

  /*------------------------------HRM-------------------------------------*/
  Route::resource('/users', 'UserController');
  Route::post('/users', 'UserController@index');
  Route::get('/user/create','UserController@roleList');
  Route::post('/users/{id}','UserController@update');
  Route::post('/user/create','UserController@store');
  Route::post('/user/get_time_estimates','UserController@getTimeEstimates');
  Route::get('/destroy/{id}','UserController@destroy');
  Route::post('/assign','UserController@assign_project');
  // Route::get('/summary','DSRController@summarylist');
  Route::any('/export_employees', 'UserController@exportEmployees')->name('export_employees');

 
  
  /*------------------------Department Routes-----------------*/
  Route::resource('/department', 'DepartmentController');
  Route::post('/department','DepartmentController@index');
  Route::post('/department/create','DepartmentController@store');
  Route::get('/department_status/{id}','DepartmentController@status');
  Route::get('/department/destroy/{id}','DepartmentController@destroy');


  /*------------------------Designations Routes-----------------*/
  Route::resource('/designations', 'DesignationController');
  Route::post('/designations','DesignationController@index');
  Route::post('/designations/create','DesignationController@store');
  Route::get('/designation_status/{id}','DesignationController@status');
  Route::get('/designations/destroy/{id}','DesignationController@destroy');

   /*------------------------ Team Management Routes-----------------*/
Route::resource('/team', 'TeamController');
Route::post('/team/store', 'TeamController@store');
Route::post('/team/update', 'TeamController@update');

});

Route::prefix('attendance')->middleware(['auth', 'check_activity'])->name('attendance.')->group(function(){
  // Route::post('/time_in' , 'AttendanceController@timeInAction')->name('attendance.timein');
  Route::match(['get', 'post'], '/time_in', 'AttendanceController@timeInAction')->name('attendance.timein');
  Route::get('/time_out' , 'AttendanceController@timeOutAction')->name('attendance.timeout');
  Route::any('/list' , 'AttendanceController@index')->name('attendance.list');
  Route::any('/export_employee_attendance', 'AttendanceController@exportAllEmployeeAttendance')->name('export_employee_attendance');
  Route::any('/user-list' , 'AttendanceController@userAttendanceList')->name('attendance.userlist');
  Route::any('/user-attendance-list' , 'AttendanceController@getAttendanceList')->name('attendance.userAttendanceList');
  Route::any('/export_user_attendance', 'AttendanceController@exportUserAttendance')->name('export_user_attendance');
  Route::post('/searchSuggestions' , 'AttendanceController@searchSuggestions')->name('searchSuggestions');
  Route::get('/bio-metric-detail/{id}/{date}' , 'AttendanceController@bioMetricDetail');
  Route::get('/monthly-attendence/{id}/{date}' , 'AttendanceController@monthlyAttendenceDetail');
});

Route::prefix('tickets')->middleware(['auth', 'check_activity'])->name('tickets.')->group(function(){
  Route::get('/list' , 'TicketsController@index')->name('tickets.list');
  Route::get('/filter' , 'TicketsController@filter')->name('tickets.filter');
  Route::get('/create' , 'TicketsController@add')->name('tickets.create');
  Route::get('/edit/{ticket_id}' , 'TicketsController@edit')->name('tickets.edit');
  Route::post('/update' , 'TicketsController@update')->name('tickets.update');
  Route::get('/delete/{ticket_id}' , 'TicketsController@delete')->name('tickets.delete');
  Route::get('/close/{ticket_id}' , 'TicketsController@close')->name('tickets.close');
  Route::get('/inProgress/{ticket_id}' , 'TicketsController@inProgress')->name('tickets.inProgress');
  Route::get('/details/{user_id}/{ticket_id}' , 'TicketsController@details')->name('tickets.details');
  Route::get('/change-status' , 'TicketsController@changeStatus')->name('tickets.changeStatus');
  Route::post('/store' , 'TicketsController@store')->name('tickets.store');
  Route::any('/category_filter',array('uses' => 'TicketsController@category_filter'));
  Route::get('/delete-attachment/{attachment_id}', 'TicketsController@deleteAttachment')->name('tickets.deleteAttachment');
});

Route::prefix('it-tickets')->middleware(['auth', 'check_activity'])->name('it-tickets.')->group(function(){
  Route::get('/list' , 'ItTicketsController@index')->name('it-tickets.list');
  Route::get('/filter' , 'TicketsController@filter')->name('it-tickets.filter');
  Route::get('/create' , 'ItTicketsController@add')->name('it-tickets.create');
  Route::get('/edit/{ticket_id}' , 'ItTicketsController@edit')->name('it-tickets.edit');
  Route::post('/update' , 'ItTicketsController@update')->name('it-tickets.update');
  Route::get('/archive/{ticket_id}' , 'ItTicketsController@archive')->name('it-tickets.archive');
  Route::get('/close/{ticket_id}' , 'ItTicketsController@close')->name('it-tickets.close');
  Route::get('/inProgress/{ticket_id}' , 'ItTicketsController@inProgress')->name('it-tickets.inProgress');
  Route::get('/reopen/{ticket_id}' , 'ItTicketsController@reopenTicket')->name('it-tickets.reopen');
  Route::get('/details/{user_id}/{ticket_id}' , 'ItTicketsController@details')->name('it-tickets.details');
  Route::post('/store' , 'ItTicketsController@store')->name('it-tickets.store');
  Route::get('/change-status' , 'ItTicketsController@changeStatus')->name('it-tickets.changeStatus');
  Route::get('/dashboard' , 'ItTicketsController@dashboard')->name('it-tickets.dashboard');
  Route::get('/delete-attachment/{attachment_id}', 'ItTicketsController@deleteAttachment')->name('it-tickets.deleteAttachment');
});
  

Route::prefix('ticket/replies')->middleware(['auth', 'check_activity'])->name('tickets_replies.')->group(function(){
  Route::post('/store' , 'TicketRepliesController@store')->name('tickets_reply.store');
  Route::post('/edit' , 'TicketRepliesController@edit')->name('tickets_reply.edit');
  Route::get('/delete/{id}' , 'TicketRepliesController@delete')->name('tickets_reply.delete');
  Route::get('/delete-attachment/{attachment_id}', 'TicketRepliesController@deleteAttachment')->name('it-tickets.deleteAttachment');
});
//documrnt upload
Route::resource('/document', 'DocumentController');
Route::get('document/delete/{id}', 'DocumentController@destroy');
Route::get('/display_pdf/{id}','DocumentController@display_pdf')->name('display_pdf');
Route::post('/page_count_time','DocumentController@time_pages')->name('time_pages');
Route::get('/reuest_genrate/{document_id}','DocumentController@request_password_genrate');
Route::get('document/password/{id}/{documentPassword}/{activationCode}','DocumentController@document_password');
Route::post('document/genrate_password','DocumentController@genrate_password');
Route::post('/documentview','DocumentController@documentView')->name('documentView');
Route::get('/manage/document','DocumentController@documentDetails');
Route::get('/password_history', 'DocumentController@password_history');
Route::get('/password_detail/{user_id}', 'DocumentController@password_details');
Route::get('get-document-password-details/{document_id}/{user_id}','DocumentController@document_password_details');
Route::get('/document_list/{id}', 'DocumentController@document_list')->name('document_list');
Route::get('/document_management', 'DocumentController@document_management');
Route::get('/document_users_details/{document_id}', 'DocumentController@document_users_details');
Route::get('/favorite/{document_id}', 'DocumentController@addToFavorite');
Route::get('/favorite/remove/{id}', 'DocumentController@removeFavorite');
Route::post('/request_document', 'DocumentController@requestDocument')->name('request_document');
Route::get('/request_documents', 'DocumentController@request_documents');
Route::get('/generate-document-password/{id}', 'DocumentController@generateDocumentPassword')->name('generate.document.password');
Route::get('get-employee-details/{document_id}/{user_id}','DocumentController@document_employee_details');
Route::get('get-request_document-details/{document_id}/{user_id}','DocumentController@document_request_document_details');
Route::get('document_request_export','DocumentController@document_request_export');

//end document
Route::prefix('pm')->middleware(['auth', 'check_activity' ])->name('pm.')->group(function(){
  Route::resource('/dashboard', 'PM\DashboardController');
});

Route::prefix('hr')->middleware(['auth', 'check_activity' ])->name('hr.')->group(function(){
  Route::resource('/dashboard', 'HR\DashboardController');
});


Route::get('download/{filename}', function($filename)
{

  $file_path = storage_path() .'/app/public/dsrs/'. $filename;
  if (file_exists($file_path)){

    $filename = explode('.', $filename);
    $filename[0] = "File_".time();
    $filename = implode('.',$filename);

    return Response::download($file_path, $filename, [
      'Content-Length: '. filesize($file_path)
    ]);
  }else{

    exit('Requested file does not exist on our server!');
  }
})->where('filename', '[A-Za-z0-9\-\_\.]+');


/**
 * 
 * Leave Route
 */
Route::get('generate/token', 'Admin\UserController@generate_token');
Route::name('leave.')->middleware(['auth', 'check_activity' ])->group(function(){
  // Route::resource('/leave', 'LeaveController');
   
  Route::resource('/leave', 'leaveManagementController');
  Route::get('/leave', 'leaveManagementController@index'); 
  Route::get('/cancel/leave', 'leaveManagementController@cancel_leave'); 
  Route::post('/leave/store', 'leaveManagementController@store');
  Route::post('/leave', 'leaveManagementController@index');
  Route::post('/team-leave', 'leaveManagementController@teamLeaves'); 
  Route::get('/team-leave', 'leaveManagementController@teamLeaves');
  Route::get('/my/leave/', 'leaveManagementController@myleaveRequest');
  Route::post('/my/leave/', 'leaveManagementController@myleaveRequest');
  Route::get('/leave/cancelStatus/{id}', 'leaveManagementController@cancelStatus');
  Route::post('/leave/statusUpdate/', 'leaveManagementController@statusUpdate');
  Route::post('/leave/export/', 'leaveManagementController@export');
  Route::get('autocomplete','leaveManagementController@autocomplete');
  Route::get('leave/show/','leaveManagementController@show');
  Route::post('formvalidation','leaveManagementController@formvalidation');
  Route::post('cancel_reasons/{id}', 'leaveManagementController@cancel_reason');
  // Route::get('/leave/cancel_status/{id}/{status}','LeaveController@cancelStatus');

});

/**
 * 
 * weekly report Route
 */
Route::name('weeklyreport.')->middleware(['auth'])->group(function(){
  Route::resource('/reports', 'WeeklyReportController');
  Route::any('/reports-list','WeeklyReportController@index');
  Route::get('/add_report','WeeklyReportController@add_Reports');
  Route::post('/report_data','WeeklyReportController@add_Reports');
  Route::get('/reportdetail/{id?}','WeeklyReportController@report');
  Route::get('/sent_report','WeeklyReportController@Reportsent');
  Route::get('/get_report_details/{id}','WeeklyReportController@ReportDetails');
  Route::get('/report_s/search_reports','WeeklyReportController@getReports');
  Route::get('/download/files/{file_name}', function($file_name = null)
    {
        $path = storage_path().'/app/public/dsrs/'.$file_name;
        if (file_exists($path)) {
            return Response::download($path);
        }
  });
});

Route::namespace('Admin')->middleware(['auth'])->group(function(){
  Route::get('/employees','UserController@team_members')->name('employee-list');
});

Route::namespace('Admin')->middleware(['auth'])->group(function(){
  Route::get('/team_member_chart','TeamMemberChart@index');
  // Route::get('/user_search','TeamMemberChart@index');
});


Route::prefix('reference')->middleware(['auth'])->name('reference.')->group(function(){
  Route::get('/list' , 'ReferenceController@index')->name('reference.list');
  Route::get('/create' , 'ReferenceController@add')->name('reference.create');
  Route::post('/store' , 'ReferenceController@store')->name('reference.store');
  Route::get('/edit/{id}' , 'ReferenceController@edit')->name('reference.edit');
  Route::post('/update' , 'ReferenceController@update')->name('reference.update');
  Route::get('/delete/{id}' , 'ReferenceController@delete')->name('reference.delete');
  Route::get('/reference_detail/{id}' , 'ReferenceController@getEmployeeById')->name('reference.getEmployeeById');
  Route::get('/comments/{id}', 'ReferenceController@getCommentsById')->name('reference.getCommentsById');
  Route::post('/comments/store', 'ReferenceController@storeComment')->name('reference.storeComment');
  Route::get('/rejection_reason/{id}', 'ReferenceController@rejectionReason')->name('reference.rejectionReason');
  Route::get('/cancel_reason/{id}', 'ReferenceController@cancelReason')->name('reference.cancelReason');
});
Route::get('/clear-cache', 'CacheController@clearCache')->name('clear.cache');

Route::prefix('biometric')->middleware(['auth'])->name('biometric.')->group(function(){
  Route::post('/send_attendance_mail' , 'BiometricController@send_attendance_mail')->name('biometric.send_attendance_mail');
});

