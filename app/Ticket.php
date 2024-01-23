<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'id';
    public $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'status',
        'attachment',
        'category_id'
    ];
}
