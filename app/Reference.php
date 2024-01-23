<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $table = 'reference';
    protected $primaryKey = 'id';
    public $fillable = [
        'employee_id',
        'reference_name',
        'mobile_number',
        'department',
        'experience',
        'resume',
        'resume_url',
        'interview_status',
        'reference_platform',
        'rejection_reason',
        'rejected_employee_id',
        'rounds',
        'recommendation'
    ];
}
