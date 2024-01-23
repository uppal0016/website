<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class addHolidaysList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:add_holidays_list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Holidays list will add in the database in holidays table';

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
        // Insert holiday records into the 'holidays' table using SQL
        $holidays = [
            ['title' => 'Republic Day', 'date' => '2023-01-26', 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['title' => 'Holi', 'date' => '2023-03-08', 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['title' => 'Independence Day', 'date' => '2023-08-15', 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['title' => 'Diwali', 'date' => '2023-11-13', 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        foreach ($holidays as $holiday) {
            DB::table('holidays')->insert($holiday);
        }

        $this->info('Holidays added to the holidays table.');
    }
}
