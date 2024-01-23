<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('it_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ticket_id');
            $table->string('item_id')->nullable();
            $table->integer('user_id')->unsigned();
            $table->text('message');
            $table->enum('status', ['Open', 'InProgress', 'Closed']);
            $table->string('reopen_time')->nullable();
            $table->string('attachment')->nullable();
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
        Schema::dropIfExists('it_tickets');
    }
}
