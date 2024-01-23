<?php

namespace App;

use Crypt;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    
    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
      'user_id','type_id', 'activity_id', 'message'
    ];

    public function notificationread(){
     
      return $this->hasMany('App\NotificationRead', 'notification_id');
    
    }
    public function notificationtype(){

      return $this->belongsTo('App\NotificationType','type_id');

    }

     public function comment()
    {
       
        return $this->hasMany('App\DsrComment');
    }

    public function dsr()
    {
       
        return $this->belongsTo('App\Dsr', 'activity_id');
    }

    public function user()
    {
       
      return $this->belongsTo('App\User');
    }

    public function getEnIdAttribute()
    {
        return Crypt::encrypt($this->attributes['id']);
    }
}
