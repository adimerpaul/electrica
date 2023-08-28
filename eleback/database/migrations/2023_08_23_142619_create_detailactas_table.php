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
        Schema::create('detailactas', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->string('tipo')->default('otro');
            $table->string('potencia');
            $table->unsignedBigInteger('acta_id')->nullable();
            $table->foreign('acta_id')->references('id')->on('actas');
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
        Schema::dropIfExists('detailactas');
    }
};
