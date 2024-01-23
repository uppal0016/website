<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTeamsTableNameColumnType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('teams', function (Blueprint $table) {
            $table->text('employee_id')->change();
        $table->boolean('attendance_approve', ['1', '0'])->default(0)->after('dsr_approve')->comment('1:approve,0:reject');
         });
      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
