<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = [
            /*[
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'email'      => 'superadmin@yopmail.com',
                'password'   => bcrypt('mastermind'),
                'role_id'    => 1,
                'status'     => 1,
                'is_admin'   => 1
            ],
            [
                'first_name' => 'mgmt',
                'last_name'  => 'talentelgia',
                'email'      => 'mgmnt@yopmail.com',
                'password'   => bcrypt('mastermind'),
                'role_id'    => 2,
                'status'     => 1
            ],
            [
                'first_name' => 'Rohit',
                'last_name'  => 'Gupta',
                'email'      => 'rohit@yopmail.com',
                'password'   => bcrypt('mastermind'),
                'role_id'    => 3,
                'status'     => 1
            ],
            [
                'first_name' => 'employee',
                'last_name'  => 'test',
                'email'      => 'employee@yopmail.com',
                'password'   => bcrypt('mastermind'),
                'role_id'    => 4,
                'status'     => 1
            ],
            [
                'first_name' => 'Human',
                'last_name'  => 'Resource',
                'email'      => 'hr@yopmail.com',
                'password'   => bcrypt('mastermind'),
                'role_id'    => 5,
                'status'     => 1
            ],*/
            [
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'email'      => 'documents.talentelgia@gmail.com',
                'password'   => bcrypt('T@lentelgi@Dibon'),
                'role_id'    => 1,
                'status'     => 1
            ]
        ];

        foreach ($values as $value){
            \App\User::create($value);
        }
    }
}
