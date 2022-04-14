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
            $table->string("nombre");
            $table->string("telefono");
            $table->string("direccion");
            $table->string("zona");
            $table->string("nroposte");
            $table->text("reclamo");
            $table->double("lat",11,6);
            $table->double("lng",11,6);
            $table->date("fecha");
            $table->time("hora");
            $table->string("estado")->default('EN ESPERA');
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
