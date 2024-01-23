<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddNewStatusTicketReplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_replies', function (Blueprint $table) {
            DB::statement("ALTER TABLE ticket_replies MODIFY COLUMN ticket_status ENUM('Open', 'InProgress','Closed','Reopen','Archive')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_replies', function (Blueprint $table) {
            DB::statement("ALTER TABLE ticket_replies MODIFY COLUMN ticket_status ENUM('Open', 'InProgress','Closed','Reopen')");
        });
    }
}
