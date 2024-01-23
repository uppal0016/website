<?php

namespace App;

use Crypt;
use Illuminate\Database\Eloquent\Model;

class ProjectAssigned extends Model
{
    protected $fillable = [
      
      'project_id','user_id'

   ];

    protected $table = "project_assigned";


     public function user()
    {
       
        return $this->belongsTo('App\User','user_id');
    }

     public function project()
    {
       
        return $this->belongsTo('App\Project','project_id');
    }

    public function getEnIdAttribute()
    {
        return Crypt::encrypt($this->attributes['id']);
    }

}
