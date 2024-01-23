<?php

namespace App;

use Crypt;
use Illuminate\Database\Eloquent\Model;

class WeeklyReportDetail extends Model
{
      protected $fillable = [
       'report_id',
       'project_id', 
       'task', 
       'description'
    ];

     public function dsr()
    {
       
        return $this->belongsTo('App\WeeklyReport','report_id');
    }

    public function project(){

        return $this->belongsTo('App\Project','project_id');
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

}
