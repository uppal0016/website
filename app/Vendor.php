<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
  protected $fillable = ['name','phone1','phone2','added_by','is_deleted'];

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
