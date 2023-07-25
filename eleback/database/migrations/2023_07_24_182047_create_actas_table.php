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
            $table->string('luminaria')->nullable(); //led, sodio, etc
            $table->integer('cantidad'); //
            $table->string('tecnico'); // nombre del tecnico acargo
            $table->string('observacion')->nullable(); // otro detalle
            $table->string('archivo')->nullable();  //jpg, pdf acta
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
