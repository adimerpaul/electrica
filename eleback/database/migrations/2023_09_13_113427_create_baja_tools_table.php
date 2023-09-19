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
        Schema::create('baja_tools', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('codigo');
            $table->string('motivo');
            $table->unsignedBigInteger('tool_id')->nullable();
            $table->foreign('tool_id')->references('id')->on('tools');
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
        Schema::dropIfExists('baja_tools');
    }
};
