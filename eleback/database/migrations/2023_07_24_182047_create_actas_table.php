<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('lugar'); // jv, urb , direccion, unidad edificio
            $table->string('distrito'); //1 al 5
            $table->string('tipo'); //nuevas, cambio de potencia, mantenimiento
            $table->string('luminaria'); //led, sodio, etc
            $table->integer('cantidad'); //
            $table->string('tecnico'); // nombre del tecnico acargo
            $table->string('archivo');  //jpg, pdf acta 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actas');
    }
};
