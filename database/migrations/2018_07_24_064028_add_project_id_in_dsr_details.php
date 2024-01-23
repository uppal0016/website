<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectIdInDsrDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dsr_details', function(Blueprint $table){
            $table->integer('project_id')->unsigned()->after('dsr_id');
            $table->dropColumn('project_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dsr_details', function(Blueprint $table){
            $table->dropColumn('project_id');
            $table->string('project_name')->after('dsr_id');
        });
    }
}
