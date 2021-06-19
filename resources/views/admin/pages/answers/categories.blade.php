@extends ('adminlte::page')

@section ('title' , 'Respostas Automáticas')
@section('content_header')
 <h1>Respostas Automáticas </h1> 
@stop

@section('content')
<div class="card">
        <div class="card-header">
            Filtros
        </div>
        <div class="card-body">
        <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Situação</th>
                        <th>PASSO</th>
                        <th>Sexo</th>
                        <th width="50">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>
                                {{ $category->description }}
                            </td>
                            <td>
                            {{ $category->detail }}
                            </td>
                            <td>
                            {{ $category->sex }}
                            </td>
                            <td style="width=10px;">
                                <a href="{{route('situacoes.respostas.index',$category->id)}}" class="btn btn-warning"> Respostas </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            Footer
        </div>
    </div>
@stop