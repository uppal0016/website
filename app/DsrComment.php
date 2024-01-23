<?php

namespace App;

use Crypt;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CommonMethods;

class DsrComment extends Model
{
    use CommonMethods;

    protected $fillable = [
       'dsr_id','user_id', 'comment'
    ];

    public static function saveVdRules(){
        return [
            "dsr_id" => "required",
            "comment" => "required"
        ];
    }

    public static function saveVdMessages(){
        return [
            "dsr_id.required" => "Something went wrong, please try again later.",
            "comment.required" => "Please write something."
        ];
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function notification(){

      return $this->hasMany('App\Notification','activity_id');
    
    }
    public function dsr(){
      return $this->belongsTo('App\Dsr');
    }
    /**
     * Set encrypted ids.
     *
     * @param  string  $value
     * @return string
     */
    public function getEnIdAttribute()
    {
        return Crypt::encrypt($this->attributes['id']);
    }

    public function getCreatedAtAttribute($date)
    {
        if(!empty($date)){
            $dateTime = $this->convertToLocalTz($date, 'Asia/Kolkata');
            return $dateTime->format('Y-m-d H:i:s');
        }

        return '';
        
    }

}
