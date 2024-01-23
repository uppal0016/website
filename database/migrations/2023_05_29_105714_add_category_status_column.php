<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('it_tickets', function (Blueprint $table) {
            $table->integer('category');
            $table->enum('severity', ['High', 'Medium', 'Low']);
            DB::statement("ALTER TABLE it_tickets MODIFY COLUMN status ENUM('Open', 'InProgress','Closed','Reopen')");
            $table->time('turnaround_time')->nullable();
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
            $table->dropColumn('category');
            $table->dropColumn('severity');
            $table->dropColumn('turnaround_time');
        });
    }
}
