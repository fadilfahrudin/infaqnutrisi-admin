<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'name' => 'Super Admin',
            'email' => 'administrator@semangatbantu.com',
            'password' => Hash::make('password'),
            'is_super' => 1
        ]);
        DB::table('admins')->insert([
            'name' => 'Fadil Fahrudin',
            'email' => 'fadilf@semangatbantu.com',
            'password' => Hash::make('password'),
            'is_super' => 1
        ]);
    }
}
