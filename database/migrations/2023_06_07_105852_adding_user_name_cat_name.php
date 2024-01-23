<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingUserNameCatName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('it_tickets', function (Blueprint $table) {
            $table->string('user_name')->nullable();
            $table->string('category_name')->nullable();
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
            $table->dropColumn('user_name');
            $table->dropColumn('category_name');
        });
    }
}
