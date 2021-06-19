<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRitualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rituals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->time('start');
            $table->time('end');
            $table->integer('duration');
            $table->integer('window');
            $table->unsignedBigInteger('analyze_id');
            $table->timestamps();
            $table->foreign('analyze_id')
            ->references('id')->on('analyzes')
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
        Schema::dropIfExists('rituals');
    }
}
