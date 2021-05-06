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
  <h1>Relatório <small>Fornecedor</small><button id="filtros_show" type="button" class="btn btn-default pull-right" style="display: none"><span class="glyphicon glyphicon-filter"></span> Voltar para os filtros</button></h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif
<div id="filtros" class="row col-sm-12">
  <form class="form">
  <div class="form-group col-sm-12">
    <label for="exampleInputEmail3"><span class="label label-default">Por Período de Cadastro</span></label>
  </div>
  <div class="form-group col-sm-4">
    <label for="exampleInputEmail3">Data Início:</label>
                <div class='input-group date data'>
                    <input type="text" class="form-control" id="data_inicio" name="data_inicio">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>

  </div>
  <div class="form-group col-sm-4">
    <label for="exampleInputPassword3">Data Fim:</label>
    <div class='input-group date data'>
                    <input type="text" class="form-control" id="data_fim" name="data_fim">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
            </div>

  </div>
  <div class="form-group col-sm-12">
    <label for="exampleInputEmail3"><span class="label label-default">Por Localização</span></label>
  </div>
  <div class="form-group col-sm-4">
    <label for="exampleInputPassword3">Estado:</label>
    <select class="form-control select2" id="estados">
        <option value="">Todos</option>
        @foreach($estados as $estado)
          <option value={{$estado->id}}>{{$estado->nome}}</option>
        @endforeach
    </select>
  </div>
  <div class="form-group col-sm-4">
    <label for="exampleInputPassword3">Cidade:</label>
    <select class="form-control select2" id="cidades">
    </select>
  </div>
  <div class="form-group col-sm-12">
    <button type="button" url="../../dopdf/gerar8.php" class="btn btn-primary pull-right gerarpdfrh"><span class="glyphicon glyphicon-file"></span> GERAR PDF</button>
  </div>
</form>
<hr>
</div>

<div class="row">
<div class="col-sm-12">
 <iframe id="iframe" class="col-lg-12 col-md-12 col-sm-12" style="display:none; padding: 0;border: 0" height="600px">
 </iframe>
 </div>
</div>
<div style="clear:both;"></div>

@endsection
