<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['title', 'date', 'status'];

    /*-------- Validations ---------*/
    protected static function saveHoliday(){
        return [
            'title' => 'required|string',
            'date' => 'required|date'
        ];
    }
}
