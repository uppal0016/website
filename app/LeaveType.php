<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{

    protected $table = 'leave_types';


    public $fillable = [
        'type',
        'value'
                
    ];
}   