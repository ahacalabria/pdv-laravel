@extends('layouts.padrao')

@section('scripts')
<script type="text/javascript">
  $('.data').datetimepicker({
            locale: 'pt-BR',
            format: 'L',
            allowInputToggle: true
          });
  $(".select2").select2();
</script>
@stop
@section('content')
<div class="page-header">
  <h1>Aplicar Agregado <small>Geral</small><button id="filtros_show" type="button" class="btn btn-default pull-right" style="display: none"><span class="glyphicon glyphicon-filter"></span> Voltar para os filtros</button></h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif
@if(isset($message) && !empty($message))
<div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Sucesso:</strong> {{ $message }}
      </div>
@endif
<div id="filtros" class="row col-sm-12">
<form action="/applytaxageral" class="form" method="POST">
{{ csrf_field() }}
  <div class="form-group col-sm-12">
    <label for="exampleInputEmail3"><span class="label label-default">Os dados serão aplicados em todos os produtos</span></label>
  </div>
  <div class="form-group col-sm-4">
  {!! Form::label('Fornecedor') !!}
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
      <select class="form-control" id="fornecedores" name="fornecedor_id" value="{{ old('pessoa_id', '') }}" required="required"></select>
    </div>
  </div>  
  <div class="form-group col-sm-4">
  {!! Form::label('Operação') !!}
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
      <select class="form-control" name="signal"required="required">
          <option value="1">+</option>
          <option value="0">-</option>
      </select>
    </div>
  </div>  
  <div class="form-group col-sm-4">
    <label for="exampleInputEmail3">VALOR AGREGADO:</label>
    <div class="input-group">
      <span class="input-group-addon">%</span>
      <input id="agregado_produto" type="text" class="form-control dinheiro" onChange="$('#agregado_produto_hidden').val($(this).autoNumeric('get'));" aria-label="agregado" value="{{ old('valor_agregado', '') }}" required="required">
      <input id="agregado_produto_hidden" type="hidden" name="valor_agregado">
    </div>
  </div>  
  <div class="form-group col-sm-12">
    <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-check"></span> APLICAR</button>
  </div>
</form>
<hr>
</div>

<div class="row">
<div class="col-sm-12">
 <iframe id="iframe" class="col-lg-12 col-md-12 col-sm-12" style="padding: 0;border: 0" height="600px">
 </iframe>
 </div>
</div>
<div style="clear:both;"></div>


@endsection
