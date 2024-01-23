<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    protected $table = 'technologies';
    protected $primaryKey = 'id';
    public $fillable = [
        'name',
        'status'
    ];
}
