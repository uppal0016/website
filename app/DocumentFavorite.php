<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentFavorite extends Model
{
    protected $table = 'document_favorite_table';
    protected $primaryKey = 'id';
    public $fillable = [     
        'user_id',
        'document_id',    
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
