<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttachedFile extends Model
{
    use HasFactory;

    protected $table = 'attached_files';

    protected $fillable = [
        'user_id',
        'it_ticket_id',
        'harmony_ticket_id',
        'user_id',
        'dirname',
        'basename',
        'extension',
        'url',
        'reply_id',
     ];

}
