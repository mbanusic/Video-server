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
	    DB::table('users')->insert([
		    'name' => 'marko',
		    'email' => 'mbanusic@gmail.com',
		    'password' => bcrypt('secret'),
	    ]);
	    DB::table('users')->insert([
		    'name' => 'redakcija',
		    'email' => 'redakcija@portal.net.hr',
		    'password' => bcrypt('redakcija123'),
	    ]);
    }
}
