<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
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
            "role" => "Admin"
          ],
          [
            "role" => "Management"
          ],
          [
            "role" => "Project Manager"
          ],
          [
            "role" => "Employee"
          ],
          [
            "role" => "Human Resource"
          ]
        ];

        DB::table('roles')->insert($data);
    }
}
