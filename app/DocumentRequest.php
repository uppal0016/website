<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    protected $table = 'document_request';
    protected $primaryKey = 'id';
    public $fillable = [     
        'user_id',
        'document_id',   
        'request_type' 
    ];

    public function user()
    {   
        return $this->belongsTo('App\User', 'user_id'); 
    }

    public function document()
    {
        return $this->belongsToMany(Document::class);
    }
}