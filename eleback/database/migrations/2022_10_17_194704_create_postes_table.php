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
        Schema::create('postes', function (Blueprint $table) {
            $table->id();
            $table->double('lat',11,6);
            $table->double('lng',11,6);
            $table->point('ubicacion')->nullable();
            $table->string('luminaria');
            $table->string('nroposte');
            $table->string('material');
            $table->string('cantidad');
            $table->string('altura');
            $table->string('junta')->nullable();
            $table->date('fechaplan')->nullable();
            $table->string('distrito');
            $table->string('potencia');
            $table->string('observacion')->nullable();
            $table->string('color');
            $table->string('tipo')->nullable();
            $table->string('estado')->default('ACTIVO');
            $table->string('brazo')->nullable();
            $table->string('comentario')->nullable();
            $table->boolean('revisado')->default(false);
            $table->boolean('reubicar')->default(false);
            $table->string('estado_poste')->nullable();
            $table->string('acceso')->nullable();
            $table->string('tipo_lum')->nullable();
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
        Schema::dropIfExists('postes');
    }
};
