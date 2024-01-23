<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItTicket extends Model
{
    use HasFactory;

    protected $table = 'it_tickets';
    protected $primaryKey = 'id';
    public $fillable = [
        'ticket_id',
        'user_id',
        'item_id',
        'message',
        'status',
        'attachment',
        'category',
        'severity',
        'user_name',
        'category_name',
    ];
}
