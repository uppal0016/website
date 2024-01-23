<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAvailabilityStatusInventoryItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('inventory_items')->where('is_deleted', 0)->update(['avilability_status' => 2]);
        DB::statement("ALTER TABLE inventory_items MODIFY avilability_status INT DEFAULT 0 COMMENT '1 for Assigned, 0 for spare, 2 for Damage, 3 for Scrap'");
        DB::statement("ALTER TABLE inventory_items MODIFY is_deleted INT COMMENT '1 for Active, 0 for Deactivate, 2 for Scrap'");
        
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->bigInteger('sold_amount')->nullable();
            $table->date('date_of_sold')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}