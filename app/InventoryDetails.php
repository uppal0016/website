<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryDetails extends Model
{
    protected $table = 'inventory_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'inventory_id','hardware_name', 'hardware_value'
      ];
      public function items()
      {
          return $this->hasMany(InventoryItem::class, 'inventory_id', 'inventory_id');
      }


    }