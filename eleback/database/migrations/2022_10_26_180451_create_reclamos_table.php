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
        Schema::create('reclamos', function (Blueprint $table) {
            $table->id();
            $table->string('reclamo');
            $table->date('fecha');
            $table->time('hora');
            $table->date('fechaman')->nullable();
            $table->time('horaman')->nullable();
            $table->string("estado")->default('EN ESPERA');
            $table->string("tecnico")->nullable();
            //$table->string("supervisor")->nullable();
            $table->string("actividad")->nullable();
            $table->string('tipo')->default('RECLAMO');
            /*$table->string("l70")->nullable();
            $table->string("l150")->nullable();
            $table->string("l250")->nullable();
            $table->string("r70")->nullable();
            $table->string("r150")->nullable();
            $table->string("r250")->nullable();
            $table->string("foto")->nullable();
            $table->string("base")->nullable();
            $table->string("ignitor")->nullable();
            $table->string("cable")->nullable();
            $table->string("contactor")->default('NO')->nullable();
            $table->string("termico")->default('NO')->nullable();
            $table->string("soquet")->nullable();*/

            $table->unsignedBigInteger('persona_id')->nullable();
            $table->foreign('persona_id')->references('id')->on('personas');
            $table->unsignedBigInteger('poste_id');
            $table->foreign('poste_id')->references('id')->on('postes');
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
        Schema::dropIfExists('reclamos');
    }
};
