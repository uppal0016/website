<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    protected $fillable = [
       'type_name'
   ];

    public function notification(){
      
        return $this->belongsTo('App\Notification','type_id');
    }
}
