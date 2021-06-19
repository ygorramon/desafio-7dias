<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'ritualGoodMorning',
        'criteriaRitualGoodMorning',
        'typeEatingRoutine', 
        'routineDifficulties',
        'formulaDifficulties',
        'iADifficulties',
        'gasColic',
        'weightGain',
        'energyExpenditure',
        'noticeSigns',  
        'slowDown',
        'ritualType',
        'environmentNapsLights',
        'environmentNapsNoises',
        'environmentNapsTemperature',
        'whereSleep',
        'placeBothers',
        'association',
        
        'nightRitual',
        'environmentRitualLights',
        'environmentRitualNoises',
        'environmentRitualTemperature',
        'nightSleepWakeUp',
        'nightSleepAssociation',
        'nightSleepAssociationBothers',
        'wakeUpNap',
        'conclusion',

    ];

    public function client()
    {
        return $this->hasOne('App\Models\Client');
    }

}
