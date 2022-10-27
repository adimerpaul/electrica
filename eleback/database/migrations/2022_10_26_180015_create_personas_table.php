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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string("nombre")->nullable();
            $table->string("ci")->unique();
            $table->string("telefono")->nullable();
            $table->string("direccion")->nullable();
            $table->string("zona")->nullable();
            $table->string('distrito')->nullable();
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
        Schema::dropIfExists('personas');
    }
};
