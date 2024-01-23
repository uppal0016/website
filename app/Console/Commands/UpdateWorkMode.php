<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateWorkMode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_work_mode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update work mode of users';

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
        DB::statement("
            UPDATE users SET work_mode = 'Hybrid'
            WHERE employee_code IN ('TLGT-210', 'TLGT-65', 'TLGT-350', 'TLGT-223')
        ");

        DB::statement("
            UPDATE users SET work_mode = 'WFH'
            WHERE employee_code IN ('TLGT-167', 'TLGT-254', 'TLGT-303', 'TLGT-297', 'TLGT-295', 'TLGT-161', 'TLGT-305', 'TLGT-81', 'TLGT-147', 'TLGT-302', 'TLGT-251', 'TLGT-213', 'TLGT-296', 'TLGT-356', 'TLGT-261', 'TLGT-276', 'TLGT-69', 'TLGT-119', 'TLGT-176', 'TLGT-212', 'TLGT-179', 'TLGT-247', 'TLGT-309', 'TLGT-112', 'TLGT-145', 'TLGT-354')
        ");

        DB::statement("
            UPDATE users SET work_mode = 'WFO'
            WHERE employee_code IN ('TLGT-358', 'TLGT-229', 'TLGT-260', 'TLGT-347', 'TLGT-231', 'TLGT-94', 'TLGT-300', 'TLGT-282', 'TLGT-314', 'TLGT-363', 'TLGT-360', 'TLGT-267', 'TLGT-291', 'TLGT-318', 'TLGT-98', 'TLGT-266', 'TLGT-330', 'TLGT-333', 'TLGT-323', 'TLGT-364', 'TLGT-243', 'TLGT-182', 'TLGT-290', 'TLGT-280', 'TLGT-353', 'TLGT-136', 'TLGT-352', 'TLGT-324', 'TLGT-201', 'TLGT-241', 'TLGT-361', 'TLGT-191', 'TLGT-232', 'TLGT-187', 'TLGT-256', 'TLGT-274', 'TLGT-327', 'TLGT-52', 'TLGT-120', 'TLGT-286', 'TLGT-299', 'TLGT-331', 'TLGT-262', 'TLGT-365', 'TLGT-320', 'TLGT-277', 'TLGT-165', 'TLGT-6', 'TLGT-322', 'TLGT-326', 'TLGT-236', 'TLGT-253', 'TLGT-257', 'TLGT-306', 'TLGT-72', 'TLGT-329', 'TLGT-334', 'TLGT-355', 'TLGT-345', 'TLGT-92', 'TLGT-234', 'TLGT-362', 'TLGT-312')
        ");

        $this->info('Work mode updated of users.');
    }
}
