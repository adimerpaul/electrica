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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->string('unidad');
            $table->string('destino');
            $table->string('foto');
            $table->date('fecha');
            $table->time('hora');
            $table->integer('tiempo');
            $table->date('retorno')->nullable();

            $table->string('codigo');
            $table->string('material');
            $table->string('estado')->default('PRESTAMO'); // prestamo // devuelto
            $table->string('observacion')->nullable();
            
            $table->unsignedBigInteger('tool_id');
            $table->foreign('tool_id')->references('id')->on('tools');

            $table->unsignedBigInteger('tecnico_id')->nullable();
            $table->foreign('tecnico_id')->references('id')->on('tecnicos');
            $table->unsignedBigInteger('user_id')->nullable();
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
        Schema::dropIfExists('prestamos');
    }
};
