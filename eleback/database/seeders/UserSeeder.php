<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            ["name"=>"Administrador","email"=>"admin@test.com",'password'=>Hash::make('admin')],
            ["name"=>"DANITZA RUEDA","email"=>"drueda@test.com",'password'=>Hash::make('123456')],
            ["name"=>"NELSON RODRIGUEZ","email"=>"nrodriguez@gamo.com",'password'=>Hash::make('123456')]
        ]);
    }
}
