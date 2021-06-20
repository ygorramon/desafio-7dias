@extends('adminlte::page')

@section('title', "Adicionar nova resposta")

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Planos</a></li>
        <li class="breadcrumb-item"><a href="#">#</a></li>
        <li class="breadcrumb-item"><a href="#" class="active">Detalhes</a></li>
        <li class="breadcrumb-item active"><a href="#" class="active">Novo</a></li>
    </ol>

    <h1>Adicionar nova resposta</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('situacoes.respostas.update',[$category->id, $answer->id])}}" method="post">
            @method('PUT')
                @include('admin.pages.answers._partials.form')
            </form>
        </div>
    </div>
@endsection