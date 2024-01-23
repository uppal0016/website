<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class InventoryItem extends Model
{
  
  protected $fillable = ['name','generate_id','category_id','qr_code_id','company_name','parameters','serial_no','d_o_p','vendor_id','purchase_amount','qr_image','invoice_image','added_by','is_deleted','deleted_by','sold_amount', 'avilability_status', 'date_of_sold'];
   protected $dates = ['deleted_at'];
  // mutator for name
  public function setNameAttribute($value)
  {
    $this->attributes['name'] = ucfirst($value);
  }

  // mutator for company name
  public function setCompanyNameAttribute($value)
  {
    $this->attributes['company_name'] = ucfirst($value);
  }

  // category relation
  public function category()
  {
    return $this->belongsTo('App\Category','category_id','id');
  }

  // user relation
  public function user()
  {
    return $this->belongsTo('App\User','assigned_to','id');
  }
}