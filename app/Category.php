<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $fillable = ['name','description','parameter','added_by','status'];

  /**
  *
  * @param  string  $value
  * @return string
  */
  public function setNameAttribute($value)
  {
    $this->attributes['name'] = ucfirst($value);
  }

}
