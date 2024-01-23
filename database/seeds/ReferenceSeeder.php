<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferenceSeeder extends Seeder
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
              "platform_services" =>"reference_candidate"
            ],
            [
              "platform_url" => "http://localhost:4200",
              "platform_services" =>"local_reference_candidate"
            ],
           
          ];
  
          DB::table('platform')->insert($data);
    }
}
