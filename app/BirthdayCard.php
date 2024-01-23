<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BirthdayCard extends Model
{
    protected $table = 'birthday_cards';

    protected $fillable = ['user_id','birthday_date','birthday_card','status'];

    /*-------- Validations ---------*/
    protected static function saveCard(){
        return [
            'employee_id' => 'required|integer',
            'birthday_date' => 'required|date',
            'birthday_card' => 'nullable|image|mimes:jpeg,png,jpg',
            'status' => 'required'
        ];
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }



}
