

@extends('adminlte::page')

@section('title', "Adicionar nova Análise")
@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Planos</a></li>
        <li class="breadcrumb-item"><a href="#">#</a></li>
        <li class="breadcrumb-item"><a href="#" class="active">Detalhes</a></li>
        <li class="breadcrumb-item active"><a href="#" class="active">Novo</a></li>
    </ol>

    <h1>Rotina de Sonecas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
        <div class="row">
        @foreach($client->analyzes as $analyze)
        
        <div class="col-md-6">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">DIA {{$analyze->day}} - {{$analyze->date}}</h3>
              </div>
              
              <div class="card-body">
                <h5>Horário que acordou: 
                @if($analyze->timeWokeUp>='06:00:00' && $analyze->timeWokeUp<='08:00:00' )
                <span class="badge bg-green">{{$analyze->timeWokeUp}}</span>
                @else
                <span class="badge bg-red">{{$analyze->timeWokeUp}}</span>
                @endif
                  </h5>

              <table class="table table-bordered">

                <tbody><tr>
                <th>#</th>
                <th>Horário Dormiu </th>
                <th>Horário Acordou</th>
                  <th>Duração </th>
                  <th>Janela</th>
                  <th>Janela Ideal </th>
                </tr>
               @foreach($analyze->naps as $nap)
                <tr>
                <td>Soneca {{$nap->number}}</td>
                <td> {{$nap->timeSlept}}</td>
                <td> {{$nap->timeWokeUp}}</td>

                <td>
                @if($nap->duration<40)
                <span class="badge bg-red">{{$nap->duration}}</span>
                @else
                @if($nap->duration>120 && $client->babyAge>180)
                <span class="badge bg-red">{{$nap->duration}}</span>
                @else
                <span class="badge bg-green">{{$nap->duration}}</span>
                @endif
                @endif
                </td>
                <td>
                @if($nap->window >= Helper::getJanela($client->babyAge)->janelaIdealInicio 
                && $nap->window <= Helper::getJanela($client->babyAge)->janelaIdealFim)
                <span class="badge bg-green">{{$nap->window}}</span>
                @else
                <span class="badge bg-red">{{$nap->window}}</span>
                  @endif
                </td>
                <td> < {{ Helper::getJanela($client->babyAge)->janelaIdealInicio}} e > {{Helper::getJanela($client->babyAge)->janelaIdealFim }} </td>
                </tr>
                @endforeach
                @foreach($analyze->rituals as $ritual)
                <tr><td>Ritual</td>
                <td>{{$ritual->start}}</td>
                <td>{{$ritual->end}}</td>
                <td>@if($ritual->duration > 30)
                <span class="badge bg-red">{{$ritual->duration}}</span>
                @else
                <span class="badge bg-green">{{$ritual->duration}}</span>
                @endif
                </td>
                <td>@if($ritual->window >= Helper::getJanela($client->babyAge)->janelaIdealInicio 
                && $ritual->window <= Helper::getJanela($client->babyAge)->janelaIdealFim)
                <span class="badge bg-green">{{$ritual->window}}</span>
                @else
                <span class="badge bg-red">{{$ritual->window}}</span>
                  @endif</td>

                </tr>
                @endforeach
                </tbody></table>            
              </div>
             
            </div>
            </div>
           
            @endforeach
            </div>
        </div>
    </div>
@endsection