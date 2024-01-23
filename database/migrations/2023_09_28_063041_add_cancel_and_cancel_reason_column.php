<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancelAndCancelReasonColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reference', function (Blueprint $table) {
            $table->text('cancel_reason')->nullable();        
            $table->integer('cancel_employee_id')->nullable();   
            DB::statement("ALTER TABLE reference MODIFY COLUMN interview_status ENUM('Hired', 'Pending', 'Scheduled and Ongoing', 'Rejected', 'Cancelled') DEFAULT 'Pending'");
            DB::statement("ALTER TABLE reference MODIFY COLUMN recommendation ENUM('Yes', 'No', 'Candidate Not Available') DEFAULT NULL ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reference', function (Blueprint $table) {
            // DB::statement("ALTER TABLE reference MODIFY COLUMN interview_status ENUM('Hired', 'Pending', 'Scheduled and Ongoing', 'Candidate Not Available', 'Rejected', 'Cancelled') DEFAULT 'Pending'");
        });
    }
}
