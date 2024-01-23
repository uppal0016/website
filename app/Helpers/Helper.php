<?php
namespace App\Helpers;
use DB;
use Crypt;
use App\User;
use App\Department;
use App\Designation;
use App\InventoryItem;
use App\Document;
use App\DocumentPassword ;

use App\DocumentRead ;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Helper {

  /*
  * @method       :  encryptDataId
  * @created_date :  07-08-2019
  * @purpose      :  to encrypt the data
  */
  public static function encryptDataId($id = null) {
    if ($id) {
      return Crypt::encrypt($id);
    }
    return false;
  }

  /*
  * @method       : decryptDataId
  * @created_date : 07-08-2019
  * @purpose      : to decrypt the data
  */
  public static function decryptDataId($encrypted_string = null) {
    if ($encrypted_string) {
      return Crypt::decrypt($encrypted_string);
    }
    return false;
  }

  /*
  * @method       : numberFormat
  * @created_date : 10-08-2019
  * @purpose      : get number format
  */
  public static function numberFormat($foo){
    return number_format((float)$foo, 2, '.', ',');
  }

  /*
  * @method       : dateFormat
  * @created_date : 10-08-2019
  * @purpose      : get date Format
  */
  public static function dateFormat($date, $format){
    return date($format,strtotime($date));
  }

  /*
  * @method       : generate_id
  * @created_date : 10-08-2019
  * @purpose      : get last generate id
  */
  public static function generate_id()
  {
    $latestInventoryItem = InventoryItem::orderBy('created_at','DESC')->first();
    if(!empty($latestInventoryItem))
    {
      $generate_id = $latestInventoryItem->id;
    }else
    {
      $generate_id = 0;
    }
    // $new_generate_id = str_pad($generate_id + 1, 4, "0", STR_PAD_LEFT);
    $new_generate_id = $generate_id + 1;
    return $new_generate_id;
  }

  /*
  * @method       : getTablaDataOrderBy
  * @created_date : 10-08-2019
  * @purpose      : get Tabla Data
  */
  public static function getTablaDataOrderBy($table,$orderBy_col,$orderBy,$whereCondition=null){
    if($whereCondition != '')
    $arr = DB::table($table)->where($whereCondition)->orderBy($orderBy_col,$orderBy)->get();
    else
    $arr = DB::table($table)->orderBy($orderBy_col,$orderBy)->get();
    return $arr;
  }
  /*
  * @method       : getTablaDataForDropDown
  * @created_date : 10-08-2019
  * @purpose      : get Tabla Data For DropDown
  */
  public static function getTablaDataForDropDown($table,$orderBy_col,$orderBy,$whereCondition=null){
    if($table == 'users')
    {
      $arr1 = DB::table($table)->where($whereCondition)->orderBy($orderBy_col,$orderBy)->pluck('id')->toArray();
      $arr2 = DB::table($table)->where($whereCondition)->orderBy($orderBy_col,$orderBy)->pluck('full_name')->toArray();
    }else {
    $arr1 = DB::table($table)->where($whereCondition)->orderBy($orderBy_col,$orderBy)->pluck('id')->toArray();
    $arr2 = DB::table($table)->where($whereCondition)->orderBy($orderBy_col,$orderBy)->pluck('name')->toArray();
  }
    $arr = array_combine($arr1,$arr2);
    return $arr;
  }

  /*
  * @method       : generate_id
  * @created_date : 02-09-2019
  * @purpose      : get last generate id
  */
  public static function emp_generate_id($id=null)
  {
    $users = User::whereNotNULL('employee_code')->get();
    $maxVal = [];
    foreach($users as $user){
      $employee_code = explode('-',$user->employee_code);
      if(isset($employee_code[1])){
        $userCode = trim($employee_code[1]); 
        $maxVal[] = $userCode;
      }
    }
    asort($maxVal);
    $maxEmpCode = max($maxVal);
    if(!empty($maxEmpCode))
    {
      $generate_id = $maxEmpCode+1;
    }else
    {
      $generate_id = 0;
    }
    $new_generate_id = 'TLGT-'.$generate_id;
    return $new_generate_id;
  
    /*
      $last_user = User::whereNotNULL('employee_code')->orderBy('id','DESC')->first();
      $user_employee_code = $last_user->employee_code;
      $employee_code = explode('-',$user_employee_code);
      $user = trim($employee_code[1]);
      if(!empty($user))
      {
        $generate_id = $user+1;
      }else
      {
        $generate_id = 0;
      }
      $new_generate_id = 'TLGT-'.$generate_id;
      return $new_generate_id;
      */
  }

  /*
  * @method       : getDesignations
  * @created_date : 02-09-2019
  * @purpose      : get designations
  */

 public static function team_lead_user(){
    
     $team = DB::table('teams')->get();
     foreach($team as $value){
     $res = str_replace( array( 
                ',' ), ' ', $value->employee_id);
     $employeeid =  explode(" ",$res);         
     $check =  in_array(Auth::user()->id,$employeeid);  
     if($check == true){
       $teamLeadid = $value->team_lead_id;
       return $teamLeadid; 
      }
     }
   
   
  }
  public static function sidebarQuery(){
    $data['dsr'] = DB::table('dsrs')->where('user_id', '=',Auth::user()->id)->where('to_ids','!=','')
           ->whereDate('created_at', date('Y-m-d'))->first(); 
    $data['weekly'] = DB::table('weekly_reports')->where('user_id', '=',Auth::user()->id)->where('to_ids','!=','')
           ->whereDate('created_at', date('Y-m-d'))->first(); 
    $data['team'] = DB::table('teams')->where('team_lead_id', '=',Auth::user()->id)->where('teams.leave_approve','=',1)->first();
    $data['team_dsr'] = DB::table('teams')->where('team_lead_id', '=',Auth::user()->id)->where('teams.dsr_approve','=',1)->first();
    $data['attendance_dsr'] = DB::table('teams')->where('team_lead_id', '=',Auth::user()->id)->where('teams.attendance_approve','=',1)->first();
      return $data;
  }
  public static function getDesignations()
  {
    return Designation::where('status',1)->pluck('name','id');
  }

  /*
  * @method       : getDepartments
  * @created_date : 02-09-2019
  * @purpose      : get departments
  */
  public static function getDepartments()
  {
    return Department::where('status',1)->pluck('name','id');
  }

  /*
  * @method       : getManagers
  * @created_date : 02-09-2019
  * @purpose      : get Managers
  */

  public static function totalleaveleft(){
    $totalleave = DB::table('total_leaves')->first();
    $year = date('Y')+1;
    $currentY = date('Y');
    if(date('Y-m-d') < date('Y-03-31')){
      $year = date('Y');
      $currentY = date('Y')-1;
    }
    if($totalleave->session_type == '1April-31March'){    
    $currentyear =  date(''.$currentY.'-03-31');  
    $nextyear =     date(''.$year.'-04-01');  
    }else{     
    $currentyear =  date('Y-12-31');  
    $nextyear =     date(''.$year.'-01-01');  
    }      
    $query1  = DB::table('leaves')->select(['leaves.*','leave_types.type as leave_type','users.first_name','users.last_name','users.employee_code','leave_types.value'])
   ->leftJoin('leave_types','leave_types.id','=','leaves.leave_type_id')
   ->leftJoin('users','users.id','=','leaves.users_id')   
   ->where('leaves.users_id','=',Auth::user()->id)
   ->where('leaves.end_date','>',$currentyear)
   ->where('leaves.end_date','<',$nextyear);
   $query2  =  DB::table('leaves')->select(['leaves.*','leave_types.type as leave_type','users.first_name','users.last_name','users.employee_code','leave_types.value'])
   ->leftJoin('leave_types','leave_types.id','=','leaves.leave_type_id')
   ->leftJoin('users','users.id','=','leaves.users_id')   
   ->where('leaves.users_id','=',Auth::user()->id)
   ->where('leaves.end_date','>',$currentyear)
   ->where('leaves.end_date','<',$nextyear);
  
   //getting approved leave count
   $query = $query1->where('leaves.leave_status', '=', 'approved')
     ->where('leaves.request_type', '=', 'leave_request');

     $endProbation = Auth::user()->end_probation;
     if ($endProbation !== null) {
         $query = $query->where('leaves.start_date', '>', $endProbation);
     }

     $totalLeaveapproved = $query->orderBy('id', 'DESC')->get();


     $totalcanclapproved = $query2->where('leaves.leave_status','=','approved')->where('leaves.request_type','=','cancel_request')->orderBy('id', 'DESC')
   ->get(); 
  
   $leavecount = [];
   $cancelcount = [];
   // request leave
   
   foreach($totalLeaveapproved as $key => $val){ 
   $begin = strtotime($val->start_date);   
   $end   = strtotime($val->end_date);  
       $days  = 0;
       while ($begin <= $end) {        
           $what_day = date("N", $begin);            
           if (!in_array($what_day, [6,7]) ) // 6 and 7 are weekend
               $days++;
           $begin += 86400; // +1 day
       }    
       $leavecount[] = $days*$val->value;  
      
   }
   //cancel leave
    foreach($totalcanclapproved as $key => $val){
    
   $begin = strtotime($val->start_date);
  
   $end   = strtotime($val->end_date);
  
       $days  = 0;
       while ($begin <= $end) {
       
           $what_day = date("N", $begin);
           
           if (!in_array($what_day, [6,7]) ) // 6 and 7 are weekend
               $days++;
           $begin += 86400; // +1 day
       }
         
       $cancelcount[] = $days*$val->value; 
 
   }
    $requestLeave  = array_sum($leavecount);
    $cancelLeave =  array_sum($cancelcount);
    if(floatval($requestLeave) > floatval($cancelLeave)) {
    $leave =  floatval($requestLeave) -  floatval($cancelLeave);
    }else{
    $leave =  0 ;
    }   
    $leaves['approvedLeave'] =  date('Y-m-d') > Auth::user()->end_probation ? $leave : 0;
    $total = intval($totalleave->total_leaves);
    $today = strtotime(date('Y-m-d'));
    $end_probation_date = strtotime(Auth::user()->end_probation); 
    
    if($today > $end_probation_date && Auth::user()->end_probation != null){
     $endOfProbation = Carbon::parse(Auth::user()->end_probation);
     $currentMonth = Carbon::now()->format('m');
     
     // Define the starting month and year of the financial year
     $financialYearStartMonth = 4; // April
     $financialYearStartYear = Carbon::now()->year;
     if ($currentMonth > $financialYearStartMonth) {
         $financialYearStartYear++;
     }
     
     // Define the ending month and year of the financial year
     $financialYearEndMonth = 3; // March
     $financialYearEndYear = $financialYearStartYear + 1;
     
     $nextApril = Carbon::createFromDate($financialYearStartYear, $financialYearStartMonth, 1);
     $nextAprilNew = Carbon::createFromDate($financialYearEndYear, $financialYearStartMonth, 1);
     
     if ($currentMonth == 4) {
       if ($nextAprilNew > $nextApril) {
           $total = min(intval($totalleave->total_leaves), $nextAprilNew->diffInMonths($nextApril)); 
       } else {
           $numOfMonths = $endOfProbation->diffInMonths($nextApril); 
           $total = min(intval($totalleave->total_leaves), $numOfMonths);
       }
   } else {
       $numOfMonths = $endOfProbation->diffInMonths($nextApril); 
       $total = min(intval($totalleave->total_leaves), $numOfMonths);
   }
   
      
   }

   if($total <  $leave){
     $leaves['extraLeave'] =   floatval($leave) -  floatval($total );
     }else{
     $leaves['extraLeave'] = 0; 
     }  
   
   $leaves['assingedLeave'] = $today > $end_probation_date ? $total : 0;
   return $leaves;

 }
  public static function interviewpanel(){    
    $ch = curl_init();   
    curl_setopt($ch, CURLOPT_URL, env('syc_interviewer_api'));   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);    
    curl_close($ch);     
     }

  public static function rapper()
  {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, env('SYNC_RAPPER_API'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
  }

  public static function documentDetail(){
         
   $documentRead = DocumentRead::getQuery()->where(['user_id'=>Auth::user()->id])->get(); 
   $total_time = [];
    foreach( $documentRead  as $val){
       $total_time[] =  $val->time;
    }
    
  $total_time  = array_sum($total_time);  
  $secs =$total_time % 60;
  $hrs =$total_time / 60;
  $mins = $hrs % 60;                                           
  $hrs = $hrs / 60;
 $total_time =  (int)$hrs . ":" . (int)$mins . ":" . (int)$secs;
  return $total_time;
  }   
  public static function getManagers()
  {
    return User::where('role_id', 3)->orWhere('designation_id', 42)->orWhere('designation_id', 43)->where('status',1)->select('first_name', 'last_name', 'id')->get();
  }

  public static function dashboardUrl()
  {
    if(Auth::user()->role_id == \App\User::ROLE_PROJECT_MANAGER || Auth::user()->role_id == \App\User::ROLE_EMPLOYEE || Auth::user()->role_id == \App\User::ROLE_HR){
      $dashboard_url = 'dashboard';
    }else{
      $dashboard_url = 'admin/dashboard';
    }
    return $dashboard_url;
  }

  public static function getAbsentUsers($useIds){
    $users = User::select('first_name','last_name')->whereNotIn('id', $useIds)->where('is_deleted', '=', 0)->get();
    return $users;
  }


}
?>
