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
        Schema::create('prestamodetalles', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('material');
            $table->string('cantidad');
            $table->unsignedBigInteger('tool_id');
            $table->foreign('tool_id')->references('id')->on('tools');
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
        Schema::dropIfExists('prestamodetalles');
    }
};
