  
@include('admin.includes.alerts')

@csrf
<div class="form-group">
    <label>Sexo:</label>
                    <select class="form-control" name="sex">
                    <option value="MASCULINO">Masculino</option>
                    <option value="FEMININO">Feminino</option>
                   </select>
    
</div>
<div class="form-group">

<label>Upload PDF: </label>
           
      <input class="form-control" type="file" name="analyze">
</div>
<div class="form-group">
    <button type="submit" class="btn btn-dark">Enviar</button>
</div>