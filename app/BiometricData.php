<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiometricData extends Model
{
    use HasFactory;
    protected $table = 'biometric_data';

    protected $fillable = [
        'check_in_time', 
        'check_out_time', 
        'serial_no',
        'employee_code_id',
        'biometric_created_on',
        'created_at',
        'updated_at',
     ];
}
