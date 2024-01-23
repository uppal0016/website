<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddNewStatusTicketHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_history', function (Blueprint $table) {
            DB::statement("ALTER TABLE ticket_history MODIFY COLUMN ticket_status ENUM('Open', 'InProgress','Closed','Reopen','Archive')");
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
            DB::statement("ALTER TABLE ticket_history MODIFY COLUMN ticket_status ENUM('Open', 'InProgress','Closed','Reopen')");
        });
    }
}
