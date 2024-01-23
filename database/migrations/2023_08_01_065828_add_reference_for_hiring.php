<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenceForHiring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reference', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_name');
            $table->bigInteger('mobile_number');
            $table->integer('employee_id')->unsigned();
            $table->string('department');
            $table->string('experience');
            $table->string('resume')->nullable();
            $table->string('reference_platform');
            $table->enum('interview_status', ['Hired', 'Pending', 'Scheduled and Ongoing', 'Rejected'])->default('Pending');
            $table->text('rejection_reason')->nullable();
            $table->integer('rejected_employee_id')->nullable();
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
        Schema::dropIfExists('reference');
    }
}
