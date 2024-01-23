<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRead extends Model
{


    protected $table = 'document_read';
    protected $primaryKey = 'id';
    public $fillable = [        
        'user_id',
        'document_id',        
        'last_page',
        'pages',
        'time',
        'max_time',
        'page_no',
        'created_at',
        'updated_at'
                    
    ];
  
    public function user()
    {   
        return $this->belongsTo('App\User', 'user_id'); 
    }

    public function document()
        {
            return $this->hasOne(Document::class, 'id', 'document_id');
        }
}