<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';
    public $fillable = [   
        'documents',
        'protected_file'
                
    ];

    public function documentRead()
        {
            return $this->hasOne(DocumentRead::class, 'document_id');
        }
    
    public function documentPassword()
        {
            return $this->hasOne(DocumentPassword::class, 'document_id');
        }

    public function favoriteTables()
        {
            return $this->belongsToMany(DocumentFavorite::class);
        }

    public function requestTables()
    {
        return $this->belongsToMany(DocumentRequest::class);
    }
}
