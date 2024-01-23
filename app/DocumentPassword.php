<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentPassword extends Model
{
    protected $table = 'document_password';
    protected $primaryKey = 'id';
    public $fillable = [        
        'user_id',
        'password',
        'document_id',
        'enable'     
    ];
}
