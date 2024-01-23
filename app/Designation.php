<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'designations';
    protected $primaryKey = 'id';
    public $fillable = [
        'name',
        'status'
    ];
}
