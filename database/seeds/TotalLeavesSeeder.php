<?php

use Illuminate\Database\Seeder;

class TotalLeavesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(DB::table('total_leaves')->count() == 0){
            DB::table('total_leaves')->insert([
    
                [
                    'session_type' => '1April-31March',
                    'year' => 2022,
                    'total_leaves' => 12,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                
    
            ]);
            
        }
            else {
             echo "\e[31mTable is not empty, therefore NOT "; 
            }
    }
}
