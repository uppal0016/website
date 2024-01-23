<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherCategoriesHarmonyTicket extends Model
{
    use HasFactory;
    protected $table = 'other_categories_harmony_tickets';

    protected $fillable = [
        'harmony_ticket_id', 'user_id','cat_name'
     ];
}
