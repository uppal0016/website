<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTicketHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });

        Schema::table('ticket_replies', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        
        
        Schema::create('ticket_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->foreign('ticket_id')->references('id')->on('tickets');            
            $table->integer('user_id');
            $table->unsignedBigInteger('reply_id');
            $table->foreign('reply_id')->references('id')->on('ticket_replies');    
            $table->enum('ticket_status', ['Open', 'InProgress', 'Closed']);
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
        Schema::dropIfExists('ticket_history');
    }
}
