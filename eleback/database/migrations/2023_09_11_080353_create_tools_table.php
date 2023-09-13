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
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->integer('cantidad')->default(1);
            $table->integer('saldo')->default(1);
            $table->string('estado'); // ACTIVO  EN Prestamo  BAJA
            $table->string('observacion')->nullable();
            $table->unsignedBigInteger('boxtool_id')->nullable();
            $table->foreign('boxtool_id')->references('id')->on('boxtools');
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
        Schema::dropIfExists('tools');
    }
};
