<?php

namespace App;

use Crypt;
use Illuminate\Database\Eloquent\Model;

class DsrDetail extends Model
{
      protected $fillable = [
       'dsr_id','project_id', 'task', 'description','total_hours','start_time','end_time'
   ];

     public function dsr()
    {
       
        return $this->belongsTo('App\Dsr','dsr_id');
    }

    public function project(){

        return $this->belongsTo('App\Project','project_id');
    }

    // public function files(){

    //     return $this->hasMany('App\DsrFile', 'dsr_detail_id');
    // }

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
