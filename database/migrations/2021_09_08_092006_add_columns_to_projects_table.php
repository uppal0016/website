<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('client_name')->nullable()->after('start_date');
            $table->text('address')->nullable()->after('client_name');
            $table->text('physical_address')->nullable()->after('address');
            $table->unsignedInteger('project_manager')->nullable()->after('physical_address');
            $table->unsignedInteger('team_lead')->nullable()->after('project_manager');
            $table->integer('hours_approved_or_spent')->nullable()->after('team_lead');
            $table->string('project_url')->nullable()->after('hours_approved_or_spent');
            $table->string('technology')->nullable()->after('project_url');
            $table->string('dev_server_url')->nullable()->after('technology');
            $table->string('qa_server_url')->nullable()->after('dev_server_url');
            $table->string('git_or_svn')->nullable()->after('qa_server_url');
            $table->string('project_document_url')->nullable()->after('git_or_svn');
            $table->string('project_management_tool')->nullable()->after('project_document_url');
            $table->string('project_video',70)->nullable()->after('project_management_tool');
            $table->string('current_status', 100)->nullable()->after('project_video');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['client_name','address','physical_address','hours_approved_or_spent','project_manager','team_lead','project_url','technology','dev_server_url','qa_server_url',
                'git_or_svn','project_document_url','project_management_tool','project_video','current_status']);
        });

    }
}
