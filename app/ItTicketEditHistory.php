<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItTicketEditHistory extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'it_ticket_edit_history';
    protected $primaryKey = 'id';
    public $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'category',
        'severity',
        'attachment'
    ];
}
