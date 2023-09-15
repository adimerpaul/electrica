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
        Schema::create('logdetalles', function (Blueprint $table) {
            $table->id();  
            $table->date('fecha');
            $table->integer('monto');          
            $table->unsignedBigInteger('ordendetalle_id')->nullable();
            $table->foreign('ordendetalle_id')->references('id')->on('ordendetalles');
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
        Schema::dropIfExists('logdetalles');
    }
};
