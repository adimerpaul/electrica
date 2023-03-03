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
        Schema::create('detalles', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('diario_id');
            //$table->foreign('diario_id')->references('id')->on('diarios');
            $table->double('cantidad');
            $table->string('material');
            $table->integer('poste_id');
            $table->unsignedBigInteger('reclamo_id');
            $table->foreign('reclamo_id')->references('id')->on('reclamos');
            $table->unsignedBigInteger('bodega_id');
            $table->foreign('bodega_id')->references('id')->on('bodegas');
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
        Schema::dropIfExists('detalles');
    }
};
