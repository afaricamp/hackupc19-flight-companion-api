<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('flights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number');
            $table->string('departure')->nullable();
            $table->string('arrival')->nullable();
            $table->string('status')->nullable();
            $table->bigInteger('score')->default(0);
            $table->float('multiplier')->default(0);
            $table->bigInteger('airport_id')->unsigned();
            $table->timestamps();

            $table->foreign('airport_id')->references('id')->on('airports');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights');
    }
}
