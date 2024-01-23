<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDesignation extends Model
{
    protected $table = 'user_designations';
    protected $primaryKey = 'id';
    public $fillable = [
        'user_id',
        'designation_id'
    ];
}
