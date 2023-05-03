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
        Schema::create('cronogramas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('actividad');
            //$table->string('distrito');
            //$table->string('junta');
            //$table->string('zona');
            $table->string('tipo');
            $table->double('cantidad');
            $table->date('fecha');
            $table->string('celular');
            $table->string('descripcion')->nullable();
            $table->unsignedBigInteger('junta_id');
            $table->foreign('junta_id')->references('id')->on('juntas');
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
        Schema::dropIfExists('cronogramas');
    }
};
