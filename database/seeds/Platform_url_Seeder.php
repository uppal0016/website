<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Platform_url_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
              "platform_url" => "http://202.164.56.84:5002", 
              "platform_services" =>"interview_panel"
            ],
            [
              "platform_url" => "http://localhost:4200",
              "platform_services" =>"local_interview_panel"
            ],
           
          ];
  
          DB::table('platform')->insert($data);
    }
}
