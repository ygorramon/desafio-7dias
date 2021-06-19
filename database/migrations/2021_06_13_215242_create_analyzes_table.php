<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analyzes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('day');
            $table->string('date');
            $table->time('timeWokeUp');
            $table->string('volcanicEffect');
            $table->text('comments');
            $table->unsignedBigInteger('client_id');
            $table->timestamps();
            $table->foreign('client_id')
                   ->references('id')->on('clients')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analyzes');
    }
}
