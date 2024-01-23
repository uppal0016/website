<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachedFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attached_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('it_ticket_id')->nullable();
            $table->foreign('it_ticket_id')->references('id')->on('it_tickets');
            $table->unsignedBigInteger('harmony_ticket_id')->nullable();
            $table->foreign('harmony_ticket_id')->references('id')->on('tickets');
            $table->integer('user_id');
            $table->string('dirname');
            $table->string('basename');
            $table->string('extension');
            $table->string('url');
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
        Schema::dropIfExists('attached_files');
    }
}
