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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('distrito');
            $table->string('junta');
            $table->string('dirigente')->nullable();
            $table->string('cargo')->nullable();
            $table->string('telefono')->nullable();
            // cantidad tipo descripcion
            /*$table->string('nueva')->nullable();// INN
            $table->string('mtto')->nullable();// MTO
            $table->string('plantado')->nullable();// PLp
            $table->string('repot')->nullable();// REP
            $table->string('otros')->nullable();// OTR
*/
            /*$table->integer('cantidad')->nullable();// cant
            $table->string('tipo')->nullable(); //tip 
            $table->string('potencia')->nullable();*/


            $table->date('fecha');
            $table->date('fechaprog')->nullable();
            $table->string('observacion',1000)->nullable();
            $table->string('estado')->default('INICIO');//INICIO // EN PROCESO// REALIZADO  // PENDIENTE //suspendido
            $table->string('usuario')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

          //  $table->unsignedBigInteger('junta_id')->nullable();
            //$table->foreign('junta_id')->references('id')->on('juntas');
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
        Schema::dropIfExists('agendas');
    }
};
