@extends ('adminlte::page')

@section ('title' , 'Análises')
@section('content_header')
 <h1>Análises </h1>
@stop

@section('content')
<div class="card">
        <div class="card-header">
            
            Passo 1
        </div>
        <div class="card-body">
        <div class="form-group">
        <div class="row">
        <div class="col-md-4">
        <label>Nome da Mãe:</label>
    <input class="form-control" readonly type="text" value="{{$client->motherName}}">
            </div>
            <div class="col-md-4">
            <label>Nome do Bebê:</label>

    <input class="form-control" readonly type="text" value="{{$client->babyName}}">
    </div>
    <div class="col-md-4">
    <label>Idade do Bebê:</label>

    <input class="form-control" readonly type="text" value="{{$client->babyAge}} dias">
    </div>
</div>
<div class="row">
<div class="col-md-4">
<label>Quantidade de Sonecas Inadequadas (curtas):</label>

    <input class="form-control" readonly type="text" value="{{$qtd_sonecas_inadequadas}}">
    </div>
    <div class="col-md-4">
    <label>Quantidade de Janelas de Sono Inadequadas:</label>
    <input class="form-control" readonly type="text" value="{{$qtd_janelas_inadequadas}}">
    </div>
    <div class="col-md-4">
    <label>Identifica Sinais de Sono:</label>
    <input class="form-control" readonly type="text" value="{{$sinais_sono}}">
    </div>
</div> 
</div>
        <div class="form-group">   
    <textarea  class="form-control" rows="15">{{ $passo1 }}</textarea>
    
</div>
        </div>
        
        <div class="card-footer">
            Footer
        </div>
    </div>

    <div class="card">
    <div class="card-header">
            
            Passo 2
        </div>
        <div class="card-body">
        <div class="form-group">
        <div class="row">
        <div class="col-md-4">
        <label>Idade do Bebê:</label>
    <input class="form-control" readonly type="text" value="{{$client->babyAge}}">
            </div>
            <div class="col-md-4">
            <label>Ganho de Peso:</label>

    <input class="form-control" readonly type="text" value="{{$ganhoPeso}}">
    </div>
    <div class="col-md-4">
    <label>CAUSAS APONTADAS - Imaturidade:</label>

    <input class="form-control" readonly type="text" value="{{$causasApontadas->imaturidade}} ">
    </div>
    <div class="col-md-4">
    <label>CAUSAS APONTADAS - Dor:</label>

    <input class="form-control" readonly type="text" value="{{$causasApontadas->dor}} ">
    </div>
    <div class="col-md-4">
    <label>CAUSAS APONTADAS - Fome:</label>

    <input class="form-control" readonly type="text" value="{{$causasApontadas->fome}} ">
    </div>
    <div class="col-md-4">
    <label>CAUSAS APONTADAS - Salto:</label>

    <input class="form-control" readonly type="text" value="{{$causasApontadas->salto}} ">
    </div>
    <div class="col-md-4">
    <label>CAUSAS APONTADAS - Angústia:</label>

    <input class="form-control" readonly type="text" value="{{$causasApontadas->angustia}} ">
    </div>
    
</div>   

    <textarea  class="form-control" rows="15">
{{$passo2->mensagem}}
    @if($passo2->imaturidade=="")
    @else
    • IMATURIDADE: {{ $passo2->imaturidade }}
    @endif
    • FOME: {{ $passo2->fome}} 

    • DOR: {{$passo2->dor}}

    • SALTO DE DESENVOLVIMENTO: {{$passo2->salto}}
    @if($passo2->angustia=="")
    @else
    • ANGÚSTIA DA SEPARAÇÃO: {{$passo2->angustia}}
@endif
    • TELAS: {{$passo2->telas}}

    • STRESS: {{$passo2->stress}}
    </textarea>
    
</div>
        </div></div>

<div class="card">
<div class="card-header">
            
            Passo 3
        </div>
        <div class="card-body">
        <div class="form-group"> 
        <label> Despertar:</label>  
    <textarea  class="form-control" rows="10">
• Horário de Despertar: {{$passo3->despertar}}
• Ritual do Bom dia: {{$passo3->ritualBomDia}}</textarea>
    </div>
    <div class="form-group"> 
        <label> Rotina Alimentar: </label>  
    <textarea  class="form-control" rows="10">
    @if($passo3->rotinaAlimentar=="")
    DIFICULDADE NA ROTINA ALIMENTAR
    {{$client->form->routineDifficulties}}
    @else
    {{$passo3->rotinaAlimentar}}
    @endif
    </textarea>
    </div>
    <div class="form-group"> 

        <label> Sonecas:</label> 
        <a target="_blank" href="{{route('analyzes.rotina',$client->id)}}" class="btn btn-warning"> Rotina de Sonecas </a>  
 
    <textarea  class="form-control" rows="10">
    @if($passo3->gastoEnergia=="")
    
    @else
    • Gasto de Energia: {{ $passo3->gastoEnergia }}
    @if($passo3->gastoEnergiaChoro=="")
    @else
    {{$passo3->gastoEnergiaChoro}}
    @endif
    @if($passo3->gastoEnergiaAcordouCedo=="")
    @else
    {{$passo3->gastoEnergiaAcordouCedo}}
    @endif
    @if($passo3->gastoEnergiaDespertares=="")
    @else
    {{$passo3->gastoEnergiaDespertares}}
    @endif
    @if($passo3->gastoEnergiaConclusao=="")
    @else
    {{$passo3->gastoEnergiaConclusao}}
    @endif
    @endif

    • Ritual de Sonecas:

    • Luzes:
    {{$passo3->ambienteSonecasLuzes}}
    • Ruídos:
    {{$passo3->ambienteSonecasRuidos}}
    • Temperatura:
    {{$passo3->ambienteSonecasTemperatura}}

    • Duração da Soneca: 
    @if($passo3->sonecasCurtas=="")
    @else
    {{$passo3->sonecasCurtas}}
    @endif
    @if($passo3->duracaoSonecas=="")
    @else
    {{$passo3->duracaoSonecas}}
    @endif
    @if($passo3->duracaoSonecasDespertar=="")
    @else
    {{$passo3->duracaoSonecasDespertar}}
    @endif
    @if($passo3->duracaoSonecasFome=="")
    @else
    {{$passo3->duracaoSonecasFome}}
    @endif
    @if($passo3->duracaoSonecasRitual=="")
    @else
    {{$passo3->duracaoSonecasRitual}}
    @endif
    
    </textarea>
    </div>
    <div class="form-group"> 
        <label> Ritual Noturno: </label>  
    <textarea  class="form-control" rows="10">
    {{$passo3->ritualNoturno}}</textarea>
    </div>
    <div class="form-group"> 
        <label> Ambiente do Sono: </label>  
    <textarea  class="form-control" rows="10">
    • LUZES: {{ $passo3->ambienteLuzes}} 

    • RUÍDOS: {{$passo3->ambienteRuidos}}

    • TEMPERATURA: {{$passo3->ambienteTemperatura}}

    </textarea>
    </div>
    </div>
</div>
        
@stop