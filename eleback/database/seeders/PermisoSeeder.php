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
        DB::table('permisos')->insert([
            ['nombre'=>'VER USUARIOS'],
            ['nombre'=>'EDITAR USUARIO'],
            ['nombre'=>'GESTIONAR PUNTOS'],
            ['nombre'=>'VISTA GENERAL'],
            ['nombre'=>'LISTA MANTENIMIENTO'],
            ['nombre'=>'VISTA DISTRITOS'],
            ['nombre'=>'LISTA DENUNCIAS'],
            ['nombre'=>'REPORTE DENUNCIAS'],
            ['nombre'=>'PLANIFICACION'],
            ['nombre'=>'TECNICOS'],
            ['nombre'=>'JUNTAS VECINALES'],
            ['nombre'=>'GESTIONAR POSTES'],
            ['nombre'=>'RECLAMOS'],
            ['nombre'=>'ALMACEN'],
            ['nombre'=>'DEPENDENCIAS/PLAZAS'],
            ['nombre'=>'REPORTE DEP/PLAZA'],
            ['nombre'=>'VER ACTAS'],
            ['nombre'=>'REGISTRAR ACTA'],
            ['nombre'=>'EDITAR ACTA'],
            ['nombre'=>'AGENDAR TRABAJOS'],
            ['nombre'=>'REPORTE MATERIAL'],
            ['nombre'=>'ELIMINAR MATERIAL'],
            ['nombre'=>'INSPECCION'],

        ]);

    }
}
