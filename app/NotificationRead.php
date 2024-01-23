<?php

namespace App;

use Crypt;
use Illuminate\Database\Eloquent\Model;

class NotificationRead extends Model
{
   /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
      'notification_id', 'user_id', 'is_read'
    ];

    protected $table ='notification_read';


    public function user()
    {   
        return $this->hasMany('App\User','user_id'); 
    }

    public function NotificationRead()
    {   
        return $this->belongsTo('App\NotificationRead'); 
    }

    public function dsr()
    {
      return $this->belongsTo('App\Dsr');
    }

    public function getEnIdAttribute()
    {
        return Crypt::encrypt($this->attributes['id']);
    }


}
