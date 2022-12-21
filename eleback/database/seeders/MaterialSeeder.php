<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('material')->insert([
            ['codigo'=>'','nombre'=>'','unidad'=>'','stock'=>'','minimo'=>'','codificar'=>'NO'],
        ]);
    }
}
