<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
           
            $table->integer('team_lead_id');
            $table->string('employee_id');
            $table->boolean('leave_approve', ['1', '0'])->default(0)->comment('1:approve,0:reject');
            $table->boolean('dsr_approve', ['1', '0'])->default(0)->comment('1:approve,0:reject');
            $table->timestamps();
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
