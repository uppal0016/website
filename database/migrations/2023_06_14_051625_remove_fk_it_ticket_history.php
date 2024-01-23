<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFkItTicketHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('it_ticket_history', function (Blueprint $table) {
            $table->dropForeign(['ticket_id']);
            $table->dropForeign(['reply_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('it_ticket_history', function (Blueprint $table) {
            $table->foreign('ticket_id')->references('id')->on('it_tickets');
            $table->foreign('reply_id')->references('id')->on('it_ticket_replies');
        });
    }
}
