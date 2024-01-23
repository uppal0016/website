<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusanddsrRejectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dsrs', function (Blueprint $table) {
            $table->boolean('status', ['1', '0','2'])->default(2)->comment('1:approve,0:reject,2:pending')->after('cc_ids');
            $table->text('dsr_rejection_reason')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dsrs', function (Blueprint $table) {
            //
        });
    }
}
