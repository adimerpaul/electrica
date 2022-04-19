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
        Schema::create('denuncias', function (Blueprint $table) {
            $table->id();
            $table->string("nombre")->nullable();
            $table->string("ci")->nullable();
            $table->string("telefono")->nullable();
            $table->string("direccion")->nullable();
            $table->string("zona")->nullable();
            $table->string("nroposte")->nullable();
            $table->text("reclamo")->nullable();
            $table->double("lat",11,6)->nullable();
            $table->double("lng",11,6)->nullable();
            $table->date("fecha")->nullable();
            $table->time("hora")->nullable();
            $table->string("estado")->default('EN ESPERA');
            $table->string("tecnico")->nullable();
            $table->string("supervisor")->nullable();
            $table->string("actividad")->nullable();
            $table->date("fechaman")->nullable();
            $table->time("horaman")->nullable();
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
        Schema::dropIfExists('denuncias');
    }
};
