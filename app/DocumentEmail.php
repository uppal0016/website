<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'document_id',
        'user_id',
        'activation_code'
    ];
}
