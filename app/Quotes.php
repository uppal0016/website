<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotes extends Model
{
    protected $table = 'quotes';

    protected $fillable = ['quote', 'created_date', 'status', 'type', 'active_date'];

    public $timestamps = false;
}
