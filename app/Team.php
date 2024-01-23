<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
   protected $table = 'teams';
    protected $primaryKey = 'id';
    public $fillable = [
        'team_lead_id',
        'employee_id',
        'leave_approve',
        'dsr_approve',
        'attendance_approve'
    ];


     protected static function saveTeam(){
        return [
               
            'employee' => 'required',

        ];
    }
}
