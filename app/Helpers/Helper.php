<?php

namespace App\Helpers;

class Helper
{

  public static function stringReplace($text,$client) {
    $nome_mae_primeiro=explode(" ",$client->motherName);
    $nome_baby_primeiro=explode(" ",$client->babyName);
    $array_from_to = array (
        'nome_mae_primeiro' => $nome_mae_primeiro[0],
        'nome_bebe_primeiro' =>$nome_baby_primeiro[0]
    );
    
    $text = str_replace(array_keys($array_from_to), $array_from_to, $text);
    return $text;
}

  public static function get_between($input, $start, $end)
        {
          $substr = substr($input, strlen($start)+strpos($input, $start), (strlen($input) - strpos($input, $end))*(-1));
          return $substr;
        }

    public static function textPDFToClient(string $text)
    {
        
        $data['motherName'] = Helper::get_between($text,"Nome da mãe: ", "Data compra" );
        $data['motherPhone'] = Helper::get_between($text,"Celular da mãe: ","E-mail da mãe: " );
        $data['motherMail'] =  Helper::get_between($text,"E-mail da mãe: ","Nome da bebê:" );
        
        $data['babyName'] = Helper::get_between($text,"Nome da bebê: ", "Data de nascimento: " );
        $data['babyBirth'] = Helper::get_between($text,"Data de nascimento: ", "Dias(idade)" );
        $data['babyAge'] = Helper::get_between($text,"Dias(idade) na data da avaliação: ", "Meses completos(idade) na data da avaliação: " );
        return (object) $data;
    }

    public static function textPDFtoForm(string $text){
    $formulario_text = Helper::get_between($text,"Formulário Final", " Fim" );          
    $formulario =[
      'ritualGoodMorning'=>trim(Helper::get_between($formulario_text,"Bom Dia bem estabelecido:", "Quais" )),
      'criteriaRitualGoodMorning'=>trim(Helper::get_between($formulario_text,"Quais critérios você cumpre do Ritual do Bom Dia:", "Rotina Alimentar:" )),
      'typeEatingRoutine'=>trim(Helper::get_between($formulario_text,"alimentar do seu bebê:", "Possui alguma dificuldade com" )), 
      'routineDifficulties'=>trim(Helper::get_between($formulario_text,"Possui alguma", "Peso do bebê:" )),
      'formulaDifficulties'=>trim(Helper::get_between($formulario_text,"Uso das Fórmulas?", "Possui alguma dificuldade " )),
      'iADifficulties'=>trim(Helper::get_between($formulario_text,"Possui alguma dificuldade com a IntroduçãoAlimentar?", "Peso do bebê:" )),
      'gasColic'=>trim(Helper::get_between($formulario_text,"Apresenta cólicas, gases, APLV ou refluxo durante anoite:", "Rotina de sonecas:" )),
      'weightGain'=>trim(Helper::get_between($formulario_text,"nos ultimos30 dias:", "Obstáculos" )),
      'energyExpenditure'=>trim(Helper::get_between($formulario_text,"Como está o gasto energético do seu bebê no tempoacordado:", "Você consegue perceber" )),
      'noticeSigns'=>trim(Helper::get_between($formulario_text,"os sinais emitidos:", "Você lembrou de" )),  
      'slowDown'=>'',
      'ritualType'=>trim(Helper::get_between($formulario_text,"choro envolvido:", "Como está seu" )),
      'environmentNapsLights'=>trim(Helper::get_between($formulario_text,"Como está seu ambiente de sonecas? Luzes:", "Como está seu ambiente de sonecas? Ruídos:" )),
      'environmentNapsNoises'=>trim(Helper::get_between($formulario_text,"Como está seu ambiente de sonecas? Ruídos:", "Como está seu ambiente de sonecas? Temperatura:" )), 
      'environmentNapsTemperature'=>trim(Helper::get_between($formulario_text,"Como está seu ambiente de sonecas? Temperatura:", "Onde dorme:" )),  
      'whereSleep'=>'',
      'placeBothers'=>'',
      'association'=>'',
      'soneca_suficiente'=>trim(Helper::get_between($text,"duração tem sido suficiente pra ele:", "Como seu bebê" )),
      'acorda_soneca'=>'',
      'nightRitual'=>'',
      'tipo_ritual'=>'',
      'environmentRitualLights'=>Helper::get_between($text," ambiente do sono noturno? Luzes:", "Como está seu ambiente do sono noturno? Ruídos:"),
      'environmentRitualNoises'=>Helper::get_between($text," ambiente do sono noturno? Ruídos:", "Como está seu ambiente do sono noturno? Temperatura:"),
      'environmentRitualTemperature'=>trim(Helper::get_between($text," ambiente do sono noturno? Temperatura:", "Sono noturno:" )),
      'nightSleepWakeUp'=>'',
      'nightSleepAssociation'=>'',
      'nightSleepAssociationBothers'=>'',
      'wakeUpNap'=>'',
      'conclusion'=>trim(Helper::get_between($text,"Quais as possíveis causas para os despertares do seubebê:", " Fim" )),  
    ];
    return  $formulario;
    }

    public static function textPDFtoAnalyze(string $text){
      $analise= array();
      for ($i=1;$i<8;$i++){
    
        $v=$i+1;
        $dia = Helper::get_between($text,"Dia ".$i, "Dia ".$v );
        if($i==7){
          $dia = Helper::get_between($text,"Dia ".$i, "Formulário Final");
        }
        $dia = $dia.'Fim';
        $sonecas_dia= Helper::get_between($text,"Dia ".$i." - Sonecas", "Dia ".$i." - Ritual Noturno" );
        $sonecas_dia= $sonecas_dia.'Fim';
        $ritual = Helper::get_between($text,"Dia ".$i." - Ritual Noturno", "Dia ".$v );
        if($i==7){
          $ritual = Helper::get_between($text,"Dia ".$i." - Ritual Noturno", "Formulário Final");
        }
        $a=0;
        $q=0;
        $data=Helper::get_between($dia,"Que dia foi feita essa avaliação: ", "Dia ".$i." - Sonecas" );
        $horario_acordou=Helper::get_between($dia,"Que horas acordou pela manhã: ", "Soneca1" );
        $efeito_vulcanico = Helper::get_between($dia,"Entrou em efeito vulcânico: ", "Observações" );
        $observacoes = Helper::get_between($dia,"Observações ", "Fim" );
      
        $sonecas = array();
        for ($a=1;$a < substr_count($sonecas_dia,'Soneca')+1;$a++){
          $q=$a+1;
          $soneca_analisada =Helper::get_between($sonecas_dia,"Soneca".$a.":", "Soneca".$q);
          if(substr_count($sonecas_dia,'Soneca')===$a){
            $soneca_analisada =Helper::get_between($sonecas_dia,"Soneca".$a.":", "Fim"); 
          }
      
         array_push($sonecas, ['number' => $a,
           'timeSlept' => Helper::get_between($soneca_analisada,"dormiu:", "- despertou:" ),
           'timeWokeUp'=>Helper::get_between($soneca_analisada,"- despertou:", "- Duração" ),
           'duration'=>Helper::get_between($soneca_analisada,"- Duração", "Min." ),
           'window' =>explode(" ", $soneca_analisada)[11],
         ]);
      
        }
      
        $ritual_noturno = array();
        $b=0;
        $r=0;
        $despertares = array();
        for ($b=1;$b < substr_count($ritual,'Despertar Noturno')+1;$b++){
          $r=$b+1;
          $despertar_analisado =Helper::get_between($ritual,"Despertar Noturno ".$b.":", "Despertar Noturno ".$r);
          if(substr_count($ritual,'Despertar Noturno')===$b){
            $despertar_analisado =Helper::get_between($ritual,"Despertar Noturno ".$b.":", "Entrou em efeito vulcânico"); 
          }
         array_push($despertares, ['number'=>$b,
          'timeWokeUp'=>Helper::get_between($despertar_analisado,"Acordou:", "- voltou" ),
          'timeSlept'=>Helper::get_between($despertar_analisado,"voltou a dormir:", "- Duração" ),
          'duration'=>Helper::get_between($despertar_analisado,"- Duração", "Min." ),
          'sleepingMode'=>substr($despertar_analisado, strpos($despertar_analisado,"Como volta a dormir:")+strlen("Como volta a dormir:")),
         ]);
           }
      
        array_push ($ritual_noturno, [
          'start' => Helper::get_between($ritual,"Horário do início:", "Hora que dormiu: " ),
          'end'=>Helper::get_between($ritual," Hora que dormiu:", "-" ),
          'duration'=>Helper::get_between($ritual,"Duração", "- Janela: " ),
          'window'=>Helper::get_between($ritual,"- Janela: ", "Min." ),
          'despertares' => $despertares,
          
        ]);
        
        array_push($analise, ['day'=>$i,
        'date' => $data,
          'timeWokeUp'=>$horario_acordou,
          'naps' => $sonecas,
          'wakes'=>$despertares,
          'ritual' => $ritual_noturno,
          'volcanicEffect'=>$efeito_vulcanico,
          'comments' =>$observacoes
        ]);
  
      
      
         
        }
      return  $analise; 
    }

    public static function getJanela($bebe_idade) {
      if($bebe_idade<59){
       $data['janelaIdealInicio']=40;
       $data['janelaIdealFim']=80;
   }
   
   if($bebe_idade>=59 && $bebe_idade<120){
       $data['janelaIdealInicio']=60;
       $data['janelaIdealFim']=90;
   }
   
   if($bebe_idade>=120 && $bebe_idade<179){
       $data['janelaIdealInicio']=75;
       $data['janelaIdealFim']=120;
   }
   if($bebe_idade>=180 && $bebe_idade<269){
       $data['janelaIdealInicio']=100;
       $data['janelaIdealFim']=150;
   }
   if($bebe_idade>=270 && $bebe_idade<359){
       $data['janelaIdealInicio']=120;
       $data['janelaIdealFim']=210;
   }
   if($bebe_idade>=360 && $bebe_idade<539){
       $data['janelaIdealInicio']=120;
       $data['janelaIdealFim']=240;
   }
   if($bebe_idade>540){
       $data['janelaIdealInicio']=120;
       $data['janelaIdealFim']=360;
   }

   return (object) $data;
   }

   public static function getGanhoPeso($bebe_idade,$ganhoPeso){
    $data="";
    
    if($bebe_idade<90 && $ganhoPeso>=700){
      $data="Adequado";
    }
    if($bebe_idade<90 && $ganhoPeso<700){
      $data="Inadequado";
    }
    if($bebe_idade>90 && $bebe_idade<=180 && $ganhoPeso>=600){
      $data="Adequado";
    }
    if($bebe_idade>90 && $bebe_idade<=180 && $ganhoPeso<600){
      $data="Inadequado";
    }
    if($bebe_idade>180 && $bebe_idade<=270 && $ganhoPeso>=500){
      $data="Adequado";
    }
    if($bebe_idade>180 && $bebe_idade<=270 && $ganhoPeso<500){
      $data="Inadequado";
    }
    if($bebe_idade>270 && $bebe_idade<=365 && $ganhoPeso>=400){
      $data="Adequado";
    }
    if($bebe_idade>270 && $bebe_idade<=365 && $ganhoPeso<400){
      $data="Inadequado";
    }
    if($ganhoPeso==""){
      $data="Vazio";
    }
    return $data;
   }

  public static function getCausasApontadas($causas) {
    if(substr_count($causas,'Imaturidade')>0){
        $data['imaturidade']='SIM';
    }
    if(substr_count($causas,'Imaturidade')==0){
       $data['imaturidade']='NÃO';
   }
   if(substr_count($causas,'Fome')>0){
       $data['fome']='SIM';
   }
   if(substr_count($causas,'Fome')==0){
      $data['fome']='NÃO';
  }
  if(substr_count($causas,'Dor')>0){
       $data['dor']='SIM';
   }
   if(substr_count($causas,'Dor')==0){
      $data['dor']='NÃO';
  }
   if(substr_count($causas,'Saltos de desenvolvimento')>0){
       $data['salto']='SIM';
   }
   if(substr_count($causas,'Saltos de desenvolvimento')==0){
      $data['salto']='NÃO';
  }
  if(substr_count($causas,'Angústia da separação')>0){
       $data['angustia']='SIM';
   }
   if(substr_count($causas,'Angústia da separação')==0){
      $data['angustia']='NÃO';
  }
  if(substr_count($causas,'Cansaço excessivo (Falta de sonecas)')>0){
       $data['cansaco']='SIM';
   }
   if(substr_count($causas,'Cansaço excessivo (Falta de sonecas)')==0){
      $data['cansaco']='NÃO';
  }
  if(substr_count($causas,'Ambiente irregular')>0){
       $data['ambiente']='SIM';
   }
   if(substr_count($causas,'Ambiente irregular')==0){
      $data['ambiente']='NÃO';
  }
  if(substr_count($causas,'Ritual inadequado')>0){
       $data['ritual']='SIM';
   }
   if(substr_count($causas,'Ritual inadequado')==0){
      $data['ritual']='NÃO';
  }
  if(substr_count($causas,'Associações')>0){
       $data['associacoes']='SIM';
   }
   if(substr_count($causas,'Associações')==0){
      $data['associacoes']='NÃO';
  }
   return (object) $data;
}

public static function getCriteriosRitual($criterios) {
  if(substr_count($criterios,'Só expõe à luz durante/após o ritual.')>0){
      $data['luzes']='SIM';
  }
  if(substr_count($criterios,'Só expõe à luz durante/após o ritual.')==0){
     $data['luzes']='NÃO';
 }
 if(substr_count($criterios,'Só expõe aos ruídos durante/após o ritual.')>0){
  $data['ruidos']='SIM';
}
if(substr_count($criterios,'Só expõe aos ruídos durante/após o ritual.')==0){
 $data['ruidos']='NÃO';
}
if(substr_count($criterios,'Só expõe aos estímulos durante/após o ritual.')>0){
  $data['estimulos']='SIM';
}
if(substr_count($criterios,'Só expõe aos estímulos durante/após o ritual.')==0){
 $data['estimulos']='NÃO';
}
if(substr_count($criterios,'Só retira do ambiente após ritual.')>0){
  $data['ambiente']='SIM';
}
if(substr_count($criterios,'Só retira do ambiente após ritual.')==0){
 $data['ambiente']='NÃO';
}
 return (object) $data;
}

public static function getDificuldadesRotina(string $routineDifficulties){
  $routineDifficulties=$routineDifficulties.'Fim';
  
    if( substr(Helper::get_between($routineDifficulties,"Aleitamento Materno?", "Fim"),0,1)=="S"){
      $data['AME']="SIM";

    }
    else{
      $data['AME']="NÃO";
    }
    if( substr(Helper::get_between($routineDifficulties,"Uso das Fórmulas?", "Fim"),0,1)=="S"){
      $data['formula']="SIM";

    }
    else{
      $data['formula']="NÃO";
    }

    if( substr(Helper::get_between($routineDifficulties,"IntroduçãoAlimentar? ", "Fim"),0,1)=="S"){
      $data['ia']="SIM";

    }
    else{
      $data['ia']="NÃO";
    }

  

  
return (object) $data;

}

}