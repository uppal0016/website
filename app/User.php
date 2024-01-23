<?php

namespace App;

use Crypt;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject 
{
    use Notifiable;

    const ROLE_ADMIN = 1;
    const ROLE_MGMT  = 2;
    const ROLE_PROJECT_MANAGER = 3;
    const ROLE_EMPLOYEE= 4;
    const ROLE_HR= 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'employee_code', 'first_name','last_name','email', 'password', 'phone_number',
         'mobile_number','address','permanent_address','dob','joining_date','pan_number','role_id','permission_id','is_deleted',
        'image','status','reporting_manager_id','reporting_manager_id2','department_id','designation_id','added_by','id','interviewPanelStatus','canScheduleInterview','shift_start_time','other_services','g_meet_link','it_ticket_dashboard','end_probation','work_mode','biometric_token'
     ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*-------- Validations ---------*/
    protected static function saveUserVd(){
        return [
            'employee_code' => 'required|unique:users',
            'first_name' => 'require d|regex:/^[a-zA-Z ]+$/',
            'last_name' => 'required|regex:/^[a-zA-Z ]+$/',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|Regex:/\A(?!.*[:;]-\))[ -~]{3,20}\z/',
            'role_id' => 'required|numeric',
            'phone_number' => 'nullable',
            'mobile_number' => 'required',
            'address' => 'required',
            'permanent_address' => 'required',
            /*'date_of_birth' => 'date',*/
            'date_of_joining' => 'required|date',
            'designations' => 'required',
            'department' => 'required',
            'reportingManager' => 'required_if:role_id,4',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'status' => 'required',
            'work_mode' => 'required',
        ];
    }

    protected static function updateUserVd($id){
        return [
            // 'employee_code' => 'required',
            'first_name' => 'required|regex:/^[a-zA-Z ]+$/',
            'last_name' => 'required|regex:/^[a-zA-Z ]+$/',
            'email' => 'required|email|unique:users,email,'.$id,
            'role_id' => 'required|numeric',
            'phone_number' => 'nullable',
            'mobile_number' => 'required',
            'address' => 'required',
            'permanent_address' => 'required',
            /*'date_of_birth' => 'date',*/
            'date_of_joining' => 'required|date',
            'designations' => 'required',
            'department' => 'required',
            'reportingManager' => 'required_if:role_id,4',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
        ];
    }

    protected static function projectTimeEstVd(){
        return [
            'user_id' => 'required',
            'project_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ];
    }

    protected static function projectTimeEstMsg(){
        return [
            'user_id.required' => 'User not found',
            'project_id.required' => 'The project field is required',
            'start_date.required' => 'The start date field is required',
            'start_date.date' => 'The start date must be a date',
            'end_date.required' => 'The end date field is required',
            'end_date.date' => 'The end date must be a date',
            'end_date.after_or_equal' => 'The end date must be equal or after start date'
        ];
    }

    protected static function changePsswrdVd(){
        return [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password'
        ];
    }

    protected static function assignUserVd(){
        return [
            'assign' => 'array',
        ];
    }


    /*-------- Validations ---------*/


    public function dsr()
    {

        return $this->hasMany('App\Dsr','user_id');
    }

    public function weeklyreport()
    {

        return $this->hasMany('App\WeeklyReport','user_id');
    }

    public function project_assign()
    {

        return $this->hasMany('App\ProjectAssigned','user_id');

    }

    public function details()
    {

        return $this->hasMany('App\DsrDetail');
    }

    public function read(){

        return $this->belongsTo('App\DsrRead');
    }

    public function notificationread(){

        return $this->belongsTo('App\NotificationRead','user_id');
    }

    public function notification(){

        return $this->belongsTo('App\Notification');
    }

    public function comment(){

      return $this->hasMany('App\DsrComment','user_id');

    }


    public function role()
    {

        return $this->belongsTo('App\Role','role_id');
    }

    public function getEnIdAttribute()
    {
        return Crypt::encrypt($this->attributes['id']);
    }

    public function getFullNameAttribute()
    {
        return $this->attributes['first_name']." ".$this->attributes['last_name'];
    }

    public function permissionRole(){

        return $this->hasMany('App\PermissionRole');
    }

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function designation()
    {
        return $this->belongsTo('App\Designation');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function favoriteTables()
    {
        return $this->belongsToMany(DocumentFavorite::class);
    }

    public function requestTables()
    {
        return $this->belongsToMany(DocumentRequest::class);
    }
}