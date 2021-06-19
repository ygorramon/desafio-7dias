<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->text('ritualGoodMorning');
            $table->text('weightGain');
            $table->text('criteriaRitualGoodMorning');
            $table->text('typeEatingRoutine');
            $table->text('routineDifficulties');
            $table->text('formulaDifficulties');
            $table->text('iADifficulties');
            $table->text('gasColic');
            $table->text('energyExpenditure');
            $table->text('noticeSigns');
            $table->text('slowDown');
            $table->text('ritualType');
            $table->text('environmentNapsLights');
            $table->text('environmentNapsNoises');
            $table->text('environmentNapsTemperature');
            $table->text('whereSleep');
            $table->text('placeBothers');
            $table->text('association');
            $table->text('wakeUpNap');
            $table->text('nightRitual');
            $table->text('environmentRitualLights');
            $table->text('environmentRitualNoises');
            $table->text('environmentRitualTemperature');
            $table->text('nightSleepWakeUp');
            $table->text('nightSleepAssociation');
            $table->text('nightSleepAssociationBothers');
            $table->text('conclusion');
           
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
        Schema::dropIfExists('forms');
    }
}
