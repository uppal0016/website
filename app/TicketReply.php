<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    protected $table = 'ticket_replies';
    protected $primaryKey = 'id';
    public $fillable = [
        'ticket_id',
        'user_id',
        'reply',
        'ticket_status'
    ];
}
