<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ITTicketHistory extends Model
{
    use HasFactory;
    protected $table = 'it_ticket_history';
    protected $primaryKey = 'id';
    public $fillable = [
        'ticket_id',
        'user_id',
        'reply_id',
        'ticket_status'
    ];
}
