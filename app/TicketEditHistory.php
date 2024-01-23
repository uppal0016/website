<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketEditHistory extends Model
{
    use HasFactory;
    protected $table = 'ticket_edit_history';
    protected $primaryKey = 'id';
    public $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'category_id',
        'attachment'
    ];
}
