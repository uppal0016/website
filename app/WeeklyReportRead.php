<?php

namespace App;

use Crypt;
use Illuminate\Database\Eloquent\Model;

class WeeklyReportRead extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
      'report_id', 
      'user_id', 
      'is_read'
    ];

    protected $table ='weekly_report_read';


    public function user()
    {   
        return $this->hasMany('App\User','user_id'); 
    }

    public function getEnIdAttribute()
    {
        return Crypt::encrypt($this->attributes['id']);
    }

}
