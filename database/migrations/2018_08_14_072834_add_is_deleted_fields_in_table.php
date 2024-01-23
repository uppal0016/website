<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsDeletedFieldsInTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('projects', function(Blueprint $table){
            $table->tinyInteger('is_deleted')->comment('0 for not deleted and 1 for deleted.')
                                             ->default(0)->after('status');
        });
        
        Schema::table('dsrs', function(Blueprint $table){
            $table->tinyInteger('is_deleted')->comment('0 for not deleted and 1 for deleted.')
                                             ->default(0)->after('cc_ids');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('projects', function(Blueprint $table){
            $table->dropColumn('is_deleted');
        });
        
        Schema::table('dsrs', function(Blueprint $table){
            $table->dropColumn('is_deleted');
        });
    }
}
