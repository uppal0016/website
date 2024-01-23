<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TotalLeave extends Model
{

    protected $table = 'total_leaves';


    public $fillable = [
        'session_type',
        'year',
        'total_leaves',
                
    ];
}    