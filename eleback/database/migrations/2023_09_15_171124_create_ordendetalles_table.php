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
        Schema::create('ordendetalles', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->string('unidad');
            $table->string('material');
            $table->string('estado')->default('PENDIENTE');
            $table->integer('entregado');
            $table->unsignedBigInteger('ordencompra_id')->nullable();
            $table->foreign('ordencompra_id')->references('id')->on('ordencompras');
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
        Schema::dropIfExists('ordendetalles');
    }
};
