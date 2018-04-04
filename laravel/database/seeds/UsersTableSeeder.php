<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\User')->create([
            'name' => 'Admin User',
            'email' => 'galagadea@gmail.com',
            'password' => bcrypt('123456'),
            'context_id'=>1,
            'type'=>'Employee' 
        ]);
       
        DB::table('role_user')->insert([
        	[
        	'id'=>1,
            'role_id' => 1,
            'user_id' => 1,
            ]
        ]);
    }
}
