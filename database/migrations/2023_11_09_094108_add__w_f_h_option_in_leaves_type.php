<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWFHOptionInLeavesType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_types', function (Blueprint $table) { 
            DB::statement("ALTER TABLE leave_types MODIFY COLUMN type ENUM('full_day', 'half_day', 'short_leave', 'WFH')");
        });

        DB::table('leave_types')->insert([
            'type' => 'WFH',
            'value' => '0',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('leave_types')
        ->where('id', '3')
        ->update([
            'type' => 'short_leave',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaves_type', function (Blueprint $table) {
            //
        });
    }
}
