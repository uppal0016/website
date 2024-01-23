<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLeave extends Model
{

    protected $table = 'leaves';


    public $fillable = [
        'users_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'leave_reason',
        'leave_rejection_reason',
        'cc_user_ids ',
        'leave_status',
        'request_type',
        'employee_id',
        'cancel_reason',
    ];
    protected static function saveLeave(){
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'cancel_leave' => 'required'
        ];
    }
}    