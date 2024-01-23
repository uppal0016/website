<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddNewStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('it_tickets', function (Blueprint $table) {
            DB::statement("ALTER TABLE it_tickets MODIFY COLUMN status ENUM('Open', 'InProgress','Closed','Reopen','Archive')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('it_tickets', function (Blueprint $table) {
            DB::statement("ALTER TABLE it_tickets MODIFY COLUMN status ENUM('Open', 'InProgress','Closed','Reopen')");
        });
    }
}
