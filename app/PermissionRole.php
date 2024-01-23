<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
  protected $fillable = [
     'permission_id', 'user_id'
  ];

  // public function permissionRole(){
  //     return $this->hasMany('App\User','user_id');
  // }

  public function user()
  {
      return $this->hasMany('App\User');
  }
}
