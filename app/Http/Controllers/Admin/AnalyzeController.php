<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Form;
use App\Models\Nap;
use App\Models\Ritual;
use App\Models\Analyze;
use App\Models\Answer;
use App\Models\Category;

use Helper;

class AnalyzeController extends Controller
{
   public function index(){
$clients= Client::all();
return view ('admin.pages.analyzes.index', ['clients'=>$clients]);
   }
   public function create(){
       return view ('admin.pages.analyzes.create');
   }
   public function store(Request $request){
            $parser = new \Smalot\PdfParser\Parser();
            $pdf    = $parser->parseFile($request->file('analyze'));
            $text = $pdf->getText();
            $text= preg_replace("/\r|\t/","", $text);
            $text= preg_replace("/\r|\n/","", $text);
           
            $text=$text.' Fim';
         
            //echo($text);
            $clientData=Helper::textPDFToClient($text);
            $formData=Helper::textPDFtoForm($text);
            $analyzeData=Helper::textPDFtoAnalyze($text);
           

            $client = Client::create([
                'motherName'=>$clientData->motherName,
                'motherPhone'=>$clientData->motherPhone,
                'motherMail'=>$clientData->motherMail,
                'babyName'=>$clientData->babyName,
                'babyBirth'=>$clientData->babyBirth,
                'babyAge'=>$clientData->babyAge,
                'babySex'=>$request['sex'],
                'status'=>"Pendente",
              ]);
           // dd($analyzeData);
           $client->form()->create($formData);
          
          // var_dump($analyzeData[0]['sonecas']);
           for ($i=0;$i<7;$i++){
           $analiseDia=$client->analyzes()->create($analyzeData[$i]);
          // var_dump($analise[$i]['sonecas']);
           $analiseDia->naps()->createMany($analyzeData[$i]['naps']);
           $analiseDia->wakes()->createMany($analyzeData[$i]['wakes']);

           $analiseDia->rituals()->create($analyzeData[$i]['ritual'][0]);
        
           }

          return redirect()->route('analyzes.index');
   }
   public function processar ($id){
      $client=Client::find($id);
      $janela = Helper::getJanela($client->babyAge);
      $qtd_janelas_inadequadas=count($client->naps->where('window','>',$janela->janelaIdealFim));
      $qtd_rituals_inicio_inadequados=count($client->rituals->where('start','>','21:00:00'));
      $qtd_sonecas_inadequadas=count($client->naps->where('duration','<',40));
      $qtd_sonecas_longas=count($client->naps->where('duration','>',120));
      $qtd_ritual_inadequado=count($client->rituals->where('duration','>',30));
      $qtd_dias_acordou_cedo=count($client->analyzes->where('timeWokeUp','<','06:00:00'));
      $qtd_dias_acordou_tarde=count($client->analyzes->where('timeWokeUp','>','08:00:00'));
      $qtd_despertares_inadequadas=count($client->wakes->where('duration','>',60));
      $acordou_mais_cedo=$client->analyzes->min('timeWokeUp');
      $acordou_mais_tarde=$client->analyzes->max('timeWokeUp');
      $ritual_bom_dia=$client->form->ritualGoodMorning;
      $criterios_Ritual_Bom_dia=Helper::getCriteriosRitual($client->form->criteriaRitualGoodMorning);
    // $qtd_janelas_inadequadas=2;
    // $qtd_sonecas_inadequadas=1;
    // $babySex='FEMININO';
    
     $sinais_sono=$client->form->noticeSigns;
     $babySex=$client->babySex;
     $ganhoPeso=$client->form->weightGain;
     $situacaoGanhoPeso=Helper::getGanhoPeso($client->babyAge,$ganhoPeso);
     
     
     $causasApontadas=Helper::getCausasApontadas($client->form->conclusion);
     $dificuldadesRotinaAlimentar=Helper::getDificuldadesRotina($client->form->routineDifficulties);
    $passo2['imaturidade']="";
    $passo2['fome']="";
    $passo2['dor']="";
    $passo2['salto']="";
    $passo2['angustia']="";
    $passo2['telas']="";
    $passo2['stress']="";
    $passo3['despertar']="";
    $passo3['ritualBomDia']="";
    $passo3['rotinaAlimentar']="";
    $passo3['ritualNoturno']="";
    $passo3['ambienteLuzes']="";
    $passo3['ambienteRuidos']="";
    $passo3['ambienteTemperatura']="";
    $passo3['gastoEnergia']="";
    $passo3['gastoEnergiaDespertares']="";
    $passo3['gastoEnergiaChoro']="";
    $passo3['gastoEnergiaAcordouCedo']="";
    $passo3['gastoEnergiaConclusao']="";
    $passo3['ambienteSonecasLuzes']="";
    $passo3['ambienteSonecasRuidos']="";
    $passo3['ambienteSonecasTemperatura']="";
    $passo3['duracaoSonecas']="";
    $passo3['duracaoSonecasDespertar']="";
    $passo3['duracaoSonecasFome']="";
    $passo3['duracaoSonecasRitual']="";
    $passo3['sonecasCurtas']="";







    



     //$sinais_sono='NÃO';

     

      if(($qtd_janelas_inadequadas==0)&&($qtd_sonecas_inadequadas==0)){
         $passo1=Category::where('sex',$babySex)
         ->where('description', 
         'PASSO 1 - Janela de sono adequada e sonecas com mais de 40 minutos')
         ->first()->answers()->get();
       }
 
       if(($qtd_janelas_inadequadas==0)&&($qtd_sonecas_inadequadas>0)){
        
         $passo1=Category::where('sex',$babySex)
         ->where('description', 
         'PASSO 1 - Janela de sono adequada e sonecas com menos de 40 minutos')
         ->first()->answers()->get();
       }
 
       if(($qtd_janelas_inadequadas>0)&&($qtd_sonecas_inadequadas==0)){
        
         if($sinais_sono=='SIM'){
           $passo1=Category::where('sex',$babySex)
           ->where('description', 
           'PASSO 1 -  Janela de sono inadequada e sonecas com mais de 40 minutos + Percebe sinais de sono')
           ->first()->answers()->get();
           
         }
         if($sinais_sono=='NÃO'){
            
           $passo1=Category::where('sex',$babySex)
           ->where('description', 
           'PASSO 1 -  Janela de sono inadequada e sonecas com mais de 40 minutos + NÃO Percebe sinais de sono')
           ->first()->answers()->get();
         }
 
       }
 
       if(($qtd_janelas_inadequadas>0)&&($qtd_sonecas_inadequadas>0)){
         if($sinais_sono=='SIM'){
            $passo1=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 1 - Janela de sono inadequada e sonecas com menos de 40 minutos + Percebe sinais de sono')
            ->first()->answers()->get();
                  }
         if($sinais_sono=='NÃO'){
            $passo1=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 1 - Janela de sono inadequada e sonecas com menos de 40 minutos + NÃO percebe sinais de sono')
            ->first()->answers()->get();
         }
 
       }

        if($client->babyAge<90){
          $passo2['imaturidade']=Category::where('sex',$babySex)
          ->where('description', 
          'PASSO 2 - IMATURIDADE')
          ->first()->answers()->get();
        }
        if($client->babyAge>=365){
          $passo2['fome']=Category::where('sex',$babySex)
          ->where('description', 
          'PASSO 2 - FOME - Maior que 1 ano')
          ->first()->answers()->get();
        }

        if($client->babyAge<365){
          if($situacaoGanhoPeso=="Adequado"){
            $passo2['fome']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 2 - FOME - Menor que 1 ano + Ganho de peso ADEQUADO')
            ->first()->answers()->get();
          }
          if($situacaoGanhoPeso=="Inadequado"){
            $passo2['fome']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 2 - FOME - Menor que 1 ano + Ganho de peso INADEQUADO')
            ->first()->answers()->get();
          }
          if($situacaoGanhoPeso=="Vazio"){
            $passo2['fome']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 2 - FOME - Menor que 1 ano + Não sabe ganho de peso')
            ->first()->answers()->get();
          }
        }
          if($causasApontadas->dor=="NÃO" && $client->babyAge<90){
            $passo2['dor']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 2 - DOR - NÃO CONFIRMADO menor que 3 meses')
            ->first()->answers()->get();
          }
          if($causasApontadas->dor=="NÃO" && $client->babyAge>=90){
            
            $passo2['dor']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 2 - DOR - NÃO CONFIRMADO maior que 3 meses')
            ->first()->answers()->get();
          }
          if($causasApontadas->dor=="SIM"){
            
            $passo2['dor']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 2 - DOR - CONFIRMADO')
            ->first()->answers()->get();
          }
          if($causasApontadas->salto=="SIM"){
            $passo2['salto']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 2 - SALTO - CONFIRMADO')
            ->first()->answers()->get();
          }
          if($causasApontadas->salto=="NÃO"){
            $passo2['salto']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 2 - SALTO - NÃO CONFIRMADO')
            ->first()->answers()->get();
          }
          if($causasApontadas->angustia=="SIM" && $client->babyAge>180){
            $passo2['angustia']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 2 - ANGÚSTIA - CONFIRMADO')
            ->first()->answers()->get();

          }
          if($causasApontadas->angustia=="NÃO" && $client->babyAge>180){
            $passo2['angustia']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 2 - ANGÚSTIA - NÃO CONFIRMADO')
            ->first()->answers()->get();

          }
          $passo2['telas']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 2 - TELAS')
            ->first()->answers()->get();

            $passo2['stress']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 2 - STRESS')
            ->first()->answers()->get();
            
            $passo2['mensagem']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 2 - MENSAGEM GERAL ')
            ->first()->answers()->get();


          if($qtd_dias_acordou_tarde>0){
            $passo3['despertar']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 3 - DESPERTAR - acorda depois de 08:00')
            ->first()->answers()->get();
          }

          if($qtd_dias_acordou_cedo>0){
            $passo3['despertar']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 3 - DESPERTAR - acorda antes de 06:00')
            ->first()->answers()->get();
          }

          if($qtd_dias_acordou_cedo==0 && $qtd_dias_acordou_tarde==0){
            if(strtotime($acordou_mais_tarde)-strtotime($acordou_mais_cedo) > '01:00:00'){
              $passo3['despertar']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - DESPERTAR - acorda entre 06:00 e 08:00 + Diferença entre o horário mais cedo e o mais tarde > 60 min')
              ->first()->answers()->get();
            }else{
              $passo3['despertar']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - DESPERTAR - acorda entre 06:00 e 08:00 + Diferença entre o horário mais cedo e o mais tarde < 60 min')
              ->first()->answers()->get();
            }
          }


            if($ritual_bom_dia=="NÃO"){
              $passo3['ritualBomDia']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - Ritual do bom dia - NÃO')
              ->first()->answers()->get();
            }
            
            if($ritual_bom_dia=="SIM"){
              if($criterios_Ritual_Bom_dia->luzes=="SIM" &&
              $criterios_Ritual_Bom_dia->ruidos=="SIM" &&
              $criterios_Ritual_Bom_dia->estimulos=="SIM" &&
              $criterios_Ritual_Bom_dia->ambiente=="SIM" ){
                $passo3['ritualBomDia']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - Ritual do bom dia - SIM + marcou tudo')
              ->first()->answers()->get();
              }else{
                $passo3['ritualBomDia']=Category::where('sex',$babySex)
                ->where('description', 
                'PASSO 3 - Ritual do bom dia - SIM + NÃO marcou tudo')
                ->first()->answers()->get();
              }
            }

          
            if($dificuldadesRotinaAlimentar->AME=="NÃO" 
            && $dificuldadesRotinaAlimentar->formula=="NÃO"
            && $dificuldadesRotinaAlimentar->ia=="NÃO"){
              $passo3['rotinaAlimentar']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - ROTINA ALIMENTAR - SEM DIFICULDADES')
              ->first()->answers()->get();
            }

            if($qtd_ritual_inadequado>0){
              $passo3['ritualNoturno']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - RITUAL NOTURNO - INADEQUADO')
              ->first()->answers()->get();
            }

            if($qtd_ritual_inadequado==0){
              if($client->form->ritualType="Sem choro"){
                $passo3['ritualNoturno']=Category::where('sex',$babySex)
                ->where('description', 
                'PASSO 3 - RITUAL NOTURNO - ADEQUADO + sem choro')
                ->first()->answers()->get();
              }else{
                $passo3['ritualNoturno']=Category::where('sex',$babySex)
                ->where('description', 
                'PASSO 3 - RITUAL NOTURNO - ADEQUADO + choro')
                ->first()->answers()->get();
              }
             
            }

            if(substr($client->form->environmentRitualLights,0,1)=="T"){
              $passo3['ambienteLuzes']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - AMBIENTE - LUZES - ADEQUADO')
              ->first()->answers()->get();
            }else{
              $passo3['ambienteLuzes']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - AMBIENTE - LUZES - INADEQUADO')
              ->first()->answers()->get();
            }

            if(substr($client->form->environmentRitualNoises,0,1)=="S"){
              $passo3['ambienteRuidos']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - AMBIENTE - RUIDOS- ADEQUADO')
              ->first()->answers()->get();
            }else{
              $passo3['ambienteRuidos']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - AMBIENTE - RUIDOS- INADEQUADO')
              ->first()->answers()->get();
            }

            if(substr($client->form->environmentRitualTemperature,0,1)=="A"){
              $passo3['ambienteTemperatura']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - AMBIENTE - TEMPERATURA- ADEQUADO')
              ->first()->answers()->get();
            }else{
              $passo3['ambienteTemperatura']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - AMBIENTE - TEMPERATURA- INADEQUADO')
              ->first()->answers()->get();
            }

            if($client->babyAge>180 ){
              if($client->form->energyExpenditure=="Adequado"){
              $passo3['gastoEnergia']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - SONECA- GASTO DE ENERGIA- ADEQUADO')
              ->first()->answers()->get();
            }else{
              $passo3['gastoEnergia']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - SONECA- GASTO DE ENERGIA- INADEQUADO')
              ->first()->answers()->get();

              if($client->form->ritualType!="Sem choro"){
                
                $passo3['gastoEnergiaChoro']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - SONECA- GASTO DE ENERGIA- INADEQUADO - Choro')
              ->first()->answers()->get();
              }
              if($qtd_despertares_inadequadas>0){
                $passo3['gastoEnergiaDespertares']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - SONECA- GASTO DE ENERGIA- INADEQUADO - Despertar')
              ->first()->answers()->get();
              }
              if($qtd_dias_acordou_cedo>0){
                $passo3['gastoEnergiaAcordouCedo']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - SONECA- GASTO DE ENERGIA- INADEQUADO - Acordou cedo')
              ->first()->answers()->get();
              }
              if($client->form->ritualType=="Sem choro" 
              && $qtd_despertares_inadequadas==0
              && $qtd_dias_acordou_cedo==0
              ){
                $passo3['gastoEnergiaConclusao']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - SONECA- GASTO DE ENERGIA- INADEQUADO - CONCLUSÃO')
              ->first()->answers()->get();
              }
            }
          }

          if(substr($client->form->environmentNapsLights,0,1)=="T"){
            $passo3['ambienteSonecasLuzes']=Category::where('sex',$babySex)
              ->where('description', 
              'PASSO 3 - SONECA- AMBIENTE- LUZES - ADEQUADO')
              ->first()->answers()->get();
          }else{
            $passo3['ambienteSonecasLuzes']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 3 - SONECA- AMBIENTE- LUZES - INADEQUADO')
            ->first()->answers()->get();
          }

          if(substr($client->form->environmentNapsNoises,0,1)=="S"){
            $passo3['ambienteSonecasRuidos']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 3 - SONECA- AMBIENTE- RUÍDOS - ADEQUADO')
            ->first()->answers()->get();
          }else{
            $passo3['ambienteSonecasRuidos']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 3 - SONECA- AMBIENTE- RUÍDOS - INADEQUADO')
            ->first()->answers()->get();
          }

          if(substr($client->form->environmentNapsTemperature,0,1)=="A"){
            $passo3['ambienteSonecasTemperatura']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 3 - SONECA- AMBIENTE- TEMPERATURA - ADEQUADO')
            ->first()->answers()->get();
          }else{
            $passo3['ambienteSonecasTemperatura']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 3 - SONECA- AMBIENTE- TEMPERATURA- INADEQUADO')
            ->first()->answers()->get();
          }

          if($qtd_sonecas_inadequadas>0){
            $passo3['sonecasCurtas']="SONECAS CURTAS";
          }
          if($qtd_sonecas_inadequadas==0 && $qtd_sonecas_longas==0){
           
            $passo3['duracaoSonecas']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 3 - SONECA- DURAÇÃO - ADEQUADO')
            ->first()->answers()->get();
            
          }
          if($qtd_sonecas_inadequadas==0 && $qtd_sonecas_longas>0){
            if($client->babyAge<=28){
              $passo3['duracaoSonecas']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 3 - SONECA- DURAÇÃO - LONGA < 28')
            ->first()->answers()->get();
            }else
            if($client->babyAge>28 && $client->babyAge<=90)
            {
              $passo3['duracaoSonecas']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 3 - SONECA- DURAÇÃO - LONGA > 28 <90')
            ->first()->answers()->get();
            }else
            if($client->babyAge>90){
              if($qtd_despertares_inadequadas>0){
              $passo3['duracaoSonecasDespertar']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 3 - SONECA- DURAÇÃO - LONGA > 90 - DESPERTAR')
            ->first()->answers()->get();
              }
              if($causasApontadas->fome=="SIM" || $situacaoGanhoPeso=="Inadequado"){
                $passo3['duracaoSonecasFome']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 3 - SONECA- DURAÇÃO - LONGA > 90 - FOME')
            ->first()->answers()->get();
              }
              if($qtd_rituals_inicio_inadequados>0){
                $passo3['duracaoSonecasRitual']=Category::where('sex',$babySex)
                ->where('description', 
                'PASSO 3 - SONECA- DURAÇÃO - LONGA > 90 - RITUAL')
                ->first()->answers()->get();
                
              }
              if($causasApontadas->fome=="NÃO" 
              && ($situacaoGanhoPeso=="Adequado" || $situacaoGanhoPeso=="")
              && $qtd_despertares_inadequadas==0
              && $qtd_rituals_inicio_inadequados==0){
                $passo3['duracaoSonecas']=Category::where('sex',$babySex)
            ->where('description', 
            'PASSO 3 - SONECA- DURAÇÃO - ADEQUADO')
            ->first()->answers()->get();
              }

            }

          }

          //dd($passo3['despertar']);

       $respostaPasso1= Helper::stringReplace($passo1[rand(0,count($passo1)-1)]->response, $client);
      
       if(!$passo2['imaturidade']==""){
        $passo2['imaturidade']= Helper::stringReplace($passo2['imaturidade'][rand(0,count($passo2['imaturidade'])-1)]->response, $client);

       }
       $passo2['fome']= Helper::stringReplace($passo2['fome'][rand(0,count($passo2['fome'])-1)]->response, $client);
       $passo2['salto']= Helper::stringReplace($passo2['salto'][rand(0,count($passo2['salto'])-1)]->response, $client);
       if(!$passo2['angustia']==""){
       $passo2['angustia']= Helper::stringReplace($passo2['angustia'][rand(0,count($passo2['angustia'])-1)]->response, $client);
       }
       $passo2['telas']= Helper::stringReplace($passo2['telas'][rand(0,count($passo2['telas'])-1)]->response, $client);
       $passo2['stress']= Helper::stringReplace($passo2['stress'][rand(0,count($passo2['stress'])-1)]->response, $client);
       $passo2['mensagem']= Helper::stringReplace($passo2['mensagem'][rand(0,count($passo2['mensagem'])-1)]->response, $client);
       $passo2['dor']= Helper::stringReplace($passo2['dor'][rand(0,count($passo2['dor'])-1)]->response, $client);
       $passo3['despertar']= Helper::stringReplace($passo3['despertar'][rand(0,count($passo3['despertar'])-1)]->response, $client);
       $passo3['ritualBomDia']= Helper::stringReplace($passo3['ritualBomDia'][rand(0,count($passo3['ritualBomDia'])-1)]->response, $client);
      if(!$passo3['rotinaAlimentar']==""){
       $passo3['rotinaAlimentar']= Helper::stringReplace($passo3['rotinaAlimentar'][rand(0,count($passo3['rotinaAlimentar'])-1)]->response, $client);
      }
      $passo3['ritualNoturno']= Helper::stringReplace($passo3['ritualNoturno'][rand(0,count($passo3['ritualNoturno'])-1)]->response, $client);
      $passo3['ambienteLuzes']= Helper::stringReplace($passo3['ambienteLuzes'][rand(0,count($passo3['ambienteLuzes'])-1)]->response, $client);
      $passo3['ambienteRuidos']= Helper::stringReplace($passo3['ambienteRuidos'][rand(0,count($passo3['ambienteRuidos'])-1)]->response, $client);
      $passo3['ambienteTemperatura']= Helper::stringReplace($passo3['ambienteTemperatura'][rand(0,count($passo3['ambienteTemperatura'])-1)]->response, $client);
      $passo3['ambienteSonecasLuzes']= Helper::stringReplace($passo3['ambienteSonecasLuzes'][rand(0,count($passo3['ambienteSonecasLuzes'])-1)]->response, $client);
      $passo3['ambienteSonecasRuidos']= Helper::stringReplace($passo3['ambienteSonecasRuidos'][rand(0,count($passo3['ambienteSonecasRuidos'])-1)]->response, $client);
      $passo3['ambienteSonecasTemperatura']= Helper::stringReplace($passo3['ambienteSonecasTemperatura'][rand(0,count($passo3['ambienteSonecasTemperatura'])-1)]->response, $client);
      
      if(!$passo3['gastoEnergia']==""){
        $passo3['gastoEnergia']= Helper::stringReplace($passo3['gastoEnergia'][rand(0,count($passo3['gastoEnergia'])-1)]->response, $client);        
      }
      if(!$passo3['gastoEnergiaChoro']==""){
        $passo3['gastoEnergiaChoro']= Helper::stringReplace($passo3['gastoEnergiaChoro'][rand(0,count($passo3['gastoEnergiaChoro'])-1)]->response, $client);        
      }
      if(!$passo3['gastoEnergiaDespertares']==""){
        $passo3['gastoEnergiaDespertares']= Helper::stringReplace($passo3['gastoEnergiaDespertares'][rand(0,count($passo3['gastoEnergiaDespertares'])-1)]->response, $client);        
      }
      if(!$passo3['gastoEnergiaAcordouCedo']==""){
        $passo3['gastoEnergiaAcordouCedo']= Helper::stringReplace($passo3['gastoEnergiaAcordouCedo'][rand(0,count($passo3['gastoEnergiaAcordouCedo'])-1)]->response, $client);                
      }
      if(!$passo3['gastoEnergiaConclusao']==""){
        $passo3['gastoEnergiaConclusao']= Helper::stringReplace($passo3['gastoEnergiaConclusao'][rand(0,count($passo3['gastoEnergiaConclusao'])-1)]->response, $client);                        
      }

      if(!$passo3['duracaoSonecas']==""){
        $passo3['duracaoSonecas']= Helper::stringReplace($passo3['duracaoSonecas'][rand(0,count($passo3['duracaoSonecas'])-1)]->response, $client);        
      }
      if(!$passo3['duracaoSonecasDespertar']==""){
        $passo3['duracaoSonecasDespertar']= Helper::stringReplace($passo3['duracaoSonecasDespertar'][rand(0,count($passo3['duracaoSonecasDespertar'])-1)]->response, $client);        
      }
      if(!$passo3['duracaoSonecasFome']==""){
        $passo3['duracaoSonecasFome']= Helper::stringReplace($passo3['duracaoSonecasFome'][rand(0,count($passo3['duracaoSonecasFome'])-1)]->response, $client);        
      }
      if(!$passo3['duracaoSonecasRitual']==""){
        $passo3['duracaoSonecasRitual']= Helper::stringReplace($passo3['duracaoSonecasRitual'][rand(0,count($passo3['duracaoSonecasRitual'])-1)]->response, $client);                
      }
      
     

       return view ('admin.pages.analyzes.processar',
       ['passo1'=>$respostaPasso1,
       'passo2'=>(object) $passo2,
       'passo3'=>(object) $passo3,
        'client'=>$client,
        'ganhoPeso'=>$ganhoPeso,
        'causasApontadas'=>$causasApontadas,
        'qtd_sonecas_inadequadas'=>$qtd_sonecas_inadequadas,
        'qtd_janelas_inadequadas' =>$qtd_janelas_inadequadas,
        'sinais_sono'=>$sinais_sono]);
   }

   public function rotina($id){
     $client=Client::find($id);
     return view ('admin.pages.analyzes.rotina', ['client'=>$client]);
   }
}
