<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTechnology extends Model
{
    protected $table = 'user_technologies';
    protected $primaryKey = 'id';
    public $fillable = [
        'user_id',
        'technology_id'
    ];
}
