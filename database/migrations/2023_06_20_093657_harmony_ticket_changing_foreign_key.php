<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HarmonyTicketChangingForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_history', function (Blueprint $table) {
            $table->foreign('ticket_id', 'harmony_ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('reply_id', 'harmony_reply_id')->references('id')->on('ticket_replies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_history', function (Blueprint $table) {
        });
    }
}
