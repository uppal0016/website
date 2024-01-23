<?php

namespace App;

use Crypt;
use Illuminate\Database\Eloquent\Model;

class DsrFile extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
      'dsr_id', 'path_name', 'original_name'
    ];

    public function getEnIdAttribute()
    {
        return Crypt::encrypt($this->attributes['id']);
    }

}
