<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Model
{
    use HasFactory;
    protected $table = 'ticket_history';
    protected $primaryKey = 'id';
    public $fillable = [
        'ticket_id',
        'user_id',
        'reply_id',
        'status'
    ];
}
