<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class MergingReportingManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:merging_reporting_manager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merging Data of reporting_manager_id2 in reporting_manager_id';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dataToMerge = User::where('reporting_manager_id2', '!=', null)->get();

        foreach ($dataToMerge as $item) {
            // Merge reporting_manager_id2 into reporting_manager_id with a comma
            $item->reporting_manager_id = $item->reporting_manager_id ? $item->reporting_manager_id . ',' . $item->reporting_manager_id2 : $item->reporting_manager_id2;

            // Save the merged data
            $item->save();        
        }

        $this->info('Data merged successfully.');
    }
}
