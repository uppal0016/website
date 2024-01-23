<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiredStatusDocumentEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_emails', function (Blueprint $table) {
            DB::statement("ALTER TABLE document_emails MODIFY COLUMN status ENUM('sent', 'resend', 'expired')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_emails', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
