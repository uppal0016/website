<?php

use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        // check if table  is empty
        if(DB::table('leave_types')->count() == 0){

            DB::table('leave_types')->insert([

                [
                    'type' => 'full_day',
                    'value' => 1.00,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'type' => 'half_day',
                    'value' => 0.50,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'type' => 'full_day',
                    'value' => 0.25,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]

            ]);
            
        } else { echo "\e[31mTable is not empty, therefore NOT "; }
    }
}
