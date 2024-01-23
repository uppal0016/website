<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CommonMethods;

class Attendance extends Model
{

    use CommonMethods;
    protected $table = 'attendance';

    protected $fillable = [
        'user_id', 
        'time_in', 
        'time_out', 
        'created_at',
        'updated_at',
        'total_working_hour',
        'late_reason'

     ];

     
    public function user_profile()
    {
        return $this->hasOne('App\User', 'id', 'user_id');

    }

    public function getTimeInAttribute($date)
    {
        
        if(!empty($date)){
            $dateTime = $this->convertToLocalTz($date, 'Asia/Kolkata');
            return $dateTime->format('Y-m-d H:i:s');
        }

        return '';
        
    }

    public function getTimeOutAttribute($date)
    {
        if(!empty($date)){
            $dateTime = $this->convertToLocalTz($date, 'Asia/Kolkata');
            return $dateTime->format('Y-m-d H:i:s');
        }

        return '';
    }

    public function biometric_data()
    {
        return $this->hasMany('App\BiometricData', 'employee_code_id', 'employee_code');

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
