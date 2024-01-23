<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HarmonyTicketEditHistoryChangeCascade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_edit_history', function (Blueprint $table) {
            $table->foreign('ticket_id', 'harmony_ticket_edit_id')->references('id')->on('tickets')->onDelete('cascade');     
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_edit_history', function (Blueprint $table) {
            //
        });
    }
}
