<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HarmonyTicketsCategories extends Seeder
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
              "name" => "Harrasment and Discrimination",
              "user_id"=>'1'
            ],  
            [
                "name" => "Workplace Safety and Security",
                "user_id"=>'1'
            ],
            [
                "name" => "Employee Relations",
                "user_id"=>'1'
            ],
            [
                "name" => "Policy Violations",
                "user_id"=>'1'
            ],
            [
                "name" => "Unfair Treatment",
                "user_id"=>'1'
            ],
            [
                "name" => "Retaliation Claims",
                "user_id"=>'1'
            ],
            [
                "name" => "Conflict Resolution",
                "user_id"=>'1'
            ],
            [
                "name" => "Bullying or Intimidation",
                "user_id"=>'1'
            ],
            [
                "name" => "Wage and Benefits",
                "user_id"=>'1'
            ],
            [
                "name" => "Work Environment",
                "user_id"=>'1'
            ],
            [
                "name" => "Health and Safety Concerns",
                "user_id"=>'1'
            ],
            [
                "name" => "Ethics Violations",
                "user_id"=>'1'
            ],
            [
                "name" => "Whistleblower Complaints",
                "user_id"=>'1'
            ],
            [
                "name" => "Leave and Accommodation Issues",
                "user_id"=>'1'
            ],
            [
                "name" => "Performance Management Concerns",
                "user_id"=>'1'
            ],
            [
                "name" => "Other",
                "user_id"=>'1'
            ],
          ];

          DB::table('harmony_tickets_categories')->insert($data);
    }
}
