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
        Schema::create('puntos', function (Blueprint $table) {
            $table->id();
//            $table->date('fecha');
//            $table->string('codigo');
//            $table->string('nroposte');
//            $table->string('descripcion');
//            $table->string('potencia');
//            $table->float('lat');
//            $table->float('lng');
//            $table->string('color');
            $table->double('lat',11,6);
            $table->double('lng',11,6);
            $table->string('nroposte');
            $table->string('material');
            $table->date('fecha');
            $table->string('descripcion');
            $table->string('cantidad');
            $table->string('codigo');
            $table->string('tecnico');
            $table->string('destino');
            $table->string('dirigente');
            $table->string('celular');
            $table->string('distrito');
            $table->string('potencia');
            $table->string('color');
            $table->string('estado')->default('ACTIVO');
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
        Schema::dropIfExists('puntos');
    }
};
