<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('role_id');
            $table->tinyInteger('is_deleted')->comment('0 for not deleted and 1 for deleted.')->default(0);
            $table->string('employee_code')->nullable()->default(null);
            $table->tinyInteger('department_id')->nullable()->default(null);
            $table->tinyInteger('designation_id')->nullable()->default(null);
            $table->string('phone_number')->nullable();
            $table->bigInteger('mobile_number')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->string('permanent_address')->nullable()->default(null);
            $table->date('dob')->nullable();
            $table->string('pan_number')->nullable();
            $table->date('joining_date')->nullable()->default(null);
            $table->string('image')->nullable();
            $table->boolean('status')->default(1);
            $table->tinyInteger('reporting_manager_id')->nullable();
            $table->bigInteger('added_by')->nullable();
            $table->boolean('is_admin', ['0', '1'])->default(0)->comment('0:Not Admin,1:Admin');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->rememberToken();
            $table->tinyInteger('reporting_manager_id2')->nullable();
            $table->string('permission_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('users');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
