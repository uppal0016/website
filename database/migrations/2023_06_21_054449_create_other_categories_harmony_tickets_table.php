<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherCategoriesHarmonyTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_categories_harmony_tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('harmony_ticket_id');  
            $table->string('cat_name'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('other_categories_harmony_tickets');
    }
}
