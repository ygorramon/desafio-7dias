@extends ('adminlte::page')

@section ('title' , 'Análises')
@section('content_header')
 <h1>Análises </h1>
@stop

@section('content')
<div class="card">
        <div class="card-header">
            
            <a href="{{ route('analyzes.create') }}" class="btn btn-dark">ADD</a>
        </div>

        <div class="card-header">
            <form action="{{ route('analyzes.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filter" placeholder="Filtrar:" class="form-control" value="{{ $filters['filter'] ?? '' }}">
                <button type="submit" class="btn btn-dark">Filtrar</button>
            </form>
        </div>
        <div class="card-body">
        <table class="table table-condensed">
                <thead>
                    <tr>
                       
                        <th>Nome da Mãe</th>
                        <th>Nome do Bebê</th>
                        <th>Status</th>
                        <th >Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            
                            <td>
                            {{ strip_tags($client->motherName) }}
                            </td>
                            <td>
                            {{ strip_tags($client->babyName) }}
                            </td>
                            <td>
                            {{ strip_tags($client->status) }}
                            </td>
                            <td >
                               <a href="{{route('analyzes.processar',$client->id)}}" class="btn btn-primary"> Processar Desafio </a>
                               <a href="{{route('analyzes.rotina',$client->id)}}" class="btn btn-warning"> Rotina de Sonecas </a>  
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            @if (isset($filters))
                {!! $clients->appends($filters)->links() !!}
            @else
                {!! $clients->links() !!}
            @endif
        </div>
    </div>
@stop