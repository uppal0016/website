<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_items', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name')->nullable();
          $table->string('generate_id');
          $table->integer('category_id')->unsigned();
          $table->string('company_name');
          $table->string('serial_no');
          $table->date('d_o_p')->nullable();
          $table->integer('vendor_id')->unsigned()->nullable();
          $table->float('purchase_amount')->nullable();
          $table->longText('parameters')->nullable();
          $table->string('invoice_image')->nullable();
          $table->integer('assigned_to')->unsigned()->nullable();
          $table->integer('added_by')->unsigned();
          $table->boolean('avilability_status')->default(0)->comment('1 for Assigned, 0 for spare');
          $table->longText('reason')->nullable();
          $table->boolean('is_deleted')->default(0)->comment('1 for Active, 0 for Deactivate');
          $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
          $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        Schema::table('inventory_items', function($table) {
          $table->foreign('category_id')->references('id')->on('categories');
        });

        Schema::table('inventory_items', function($table) {
          $table->foreign('vendor_id')->references('id')->on('vendors');
        });

        Schema::table('inventory_items', function($table) {
          $table->foreign('added_by')->references('id')->on('users');
        });

        Schema::table('inventory_items', function($table) {
          $table->foreign('assigned_to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_items');
    }
}
