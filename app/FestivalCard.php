<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FestivalCard extends Model
{
    protected $table = 'festival_cards';

    protected $fillable = ['title', 'festival_date' , 'festival_card', 'status'];


    /*-------- Validations ---------*/
    protected static function saveCard(){
        return [
            'title' => 'required|string',
            'festival_date' => 'required|date',
            'festival_card' => 'nullable|image|mimes:jpeg,png,jpg'
        ];
    }

}
