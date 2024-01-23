<?php

namespace App;

use Crypt;
use Illuminate\Database\Eloquent\Model;

class DsrRead extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
      'dsr_id', 'user_id', 'is_read'
    ];

    protected $table ='dsr_read';


    public function user()
    {   
        return $this->hasMany('App\User','user_id'); 
    }

    public function getEnIdAttribute()
    {
        return Crypt::encrypt($this->attributes['id']);
    }

}
