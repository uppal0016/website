<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItTicketsSeeder extends Seeder
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
              "name" => "Server"
            ],
            [
              "name" => "System"
            ],
            [
              "name" => "Networking"
            ],
            [
              "name" => "Software/Hardware inquiry"
            ]
          ];

          DB::table('it_ticket_categories')->insert($data);
    }
}
