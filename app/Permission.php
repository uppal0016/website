<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
  protected $fillable = ['name'];

  function getNameAttribute($value)
  {
    return ucfirst($value);
  }
}
