@section('plugins.Summernote', true)

@include('admin.includes.alerts')

@csrf
<div class="form-group">
    <label>Resposta:</label>
    <textarea  name="response" class="form-control">{{ $answer->response ?? old('response') }}</textarea>
    
</div>

<div class="form-group">
    <button type="submit" class="btn btn-dark">Enviar</button>
</div>
@section('js')
    <script type="text/javascript">
    $(document).ready(function() {
  $('#summernote').summernote();
});
    </script>
@stop