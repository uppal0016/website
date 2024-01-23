<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyReportingManagerIdLength extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('reporting_manager_id', 255)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // If needed, reverse the change
            $table->string('reporting_manager_id')->change();
        });
    }
}
