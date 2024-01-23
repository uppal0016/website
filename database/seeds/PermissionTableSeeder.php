<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
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
              "name" => "inventory"
            ],
            [
              "name" => "hrm"
            ]
          ];

          DB::table('permissions')->insert($data);
      }
}
