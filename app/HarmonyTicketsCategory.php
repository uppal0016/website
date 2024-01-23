<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HarmonyTicketsCategory extends Model
{
    use HasFactory;
    protected $table = 'harmony_tickets_categories';
    protected $fillable = ['name', 'user_id'];

}
