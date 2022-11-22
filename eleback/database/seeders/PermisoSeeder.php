<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            ['nombre'=>'VER USUARIOS'],
            ['nombre'=>'EDITAR USUARIO'],
            ['nombre'=>'GESTIONAR PUNTOS'],
            ['nombre'=>'VISTA GENERAL'],
            ['nombre'=>'LISTA MANTENIMIENTO'],
            ['nombre'=>'VISTA DISTRITOS'],
            ['nombre'=>'LISTA DENUNCIAS'],
            ['nombre'=>'REPORTE DENUNCIAS'],
        ]);

    }
}
