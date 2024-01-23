<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DsrDropDownCategories extends Seeder
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
              "name" => "Meetings",
              "status" => '1',
              "project_type" => 1
            ],
            [
                "name" => "Event",
                "status" => '1',
                "project_type" => 1
            ],
            [
                "name" => "Lunch",
                "status" => '1',
                "project_type" => 1
            ],
            [
                "name" => "Learning",
                "status" => '1',
                "project_type" => 1
            ],
            [
                "name" => "Processes",
                "status" => '1',
                "project_type" => 1
            ],
            [
                "name" => "Proposal",
                "status" => '1',
                "project_type" => 1
            ],
            [
                "name" => "Code Review/Project Review",
                "status" => '1',
                "project_type" => 1
            ],
            [
                "name" => "Interview",
                "status" => '1',
                "project_type" => 1
            ]
          ];
  
          \DB::table('projects')->insert($data);
    }
}
