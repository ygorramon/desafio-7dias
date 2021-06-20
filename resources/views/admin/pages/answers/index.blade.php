@extends ('adminlte::page')

@section ('title' , 'Respostas Automáticas')
@section('content_header')
 <h1>Respostas Automáticas </h1>
@stop

@section('content')
<div class="card">
        <div class="card-header">
            <b> Situação:</b> {{$category->description}} <b> Sexo:</b> {{$category->sex}}
            <a href="{{ route('situacoes.respostas.create', $category->id) }}" class="btn btn-dark">ADD</a>
        </div>
        <div class="card-body">
        <table class="table table-condensed">
                <thead>
                    <tr>
                       
                        <th>Resposta</th>
                        <th width="50">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($answers as $answer)
                        <tr>
                            
                            <td>
                            {{ $answer->response }}
                            </td>
                            <td style="width=10px;">
                                <a class="btn btn-warning" href="{{route('situacoes.respostas.edit', [$category->id, $answer->id])}}">
                                Editar
                                </a>
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