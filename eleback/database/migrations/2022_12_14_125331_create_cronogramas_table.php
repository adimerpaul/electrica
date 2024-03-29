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
            $table->string('distrito');
            $table->string('junta');
            //$table->string('codigo');
            $table->string('dirigente')->nullable();
            $table->string('telefono')->nullable();
            //$table->string('actividad');

            $table->string('nueva')->nullable();
            $table->string('mtto')->nullable();
            $table->string('plantado')->nullable();
            $table->string('repot')->nullable();
            $table->string('otros')->nullable();
            $table->date('fecha');
            //$table->string('tipo');
            //$table->double('cantidad');
            //$table->string('celular');
            //$table->string('descripcion')->nullable();
            $table->string('observacion')->nullable();
            $table->string('estado')->default('INICIO');//INICIO // EN PROCESO// REALIZADO  // PENDIENTE
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('junta_id')->nullable();
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
