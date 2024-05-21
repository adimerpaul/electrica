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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->double('cantidad')->default(1);
            $table->string('codigo');
            $table->string('letra')->nullable();
            $table->integer('num')->nullable();
            $table->string('norden')->nullable();
            $table->string('tiporden')->nullable();
            $table->string('gestion')->nullable();
            $table->string('estado')->default('ACTIVO');
            $table->unsignedBigInteger('material_id');
            $table->foreign('material_id')->references('id')->on('materials');
            $table->unsignedBigInteger('compra_id');
            $table->foreign('compra_id')->references('id')->on('compras');

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
        Schema::dropIfExists('inventarios');
    }
};
