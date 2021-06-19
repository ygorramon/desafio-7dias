

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

    <h1>Adicionar nova análise</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('analyzes.store')}}" method="post" enctype="multipart/form-data">
                @include('admin.pages.analyzes._partials.form')
            </form>
        </div>
    </div>
@endsection