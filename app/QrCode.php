<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $table = 'qr_codes';
    protected $primaryKey = 'id';
    public $fillable = [
        'qr_image',
        'genrated_id',
        'status',        
    ];

}
