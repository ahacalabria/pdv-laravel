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
  <h1>Relatório <small>Geral de Vendas</small><button id="filtros_show" type="button" class="btn btn-default pull-right" style="display: none"><span class="glyphicon glyphicon-filter"></span> Voltar para os filtros</button></h1>
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
    <label for="exampleInputEmail3"><span class="label label-default">Por ID</span></label>
  </div>
  <div class="form-group col-sm-4">
    <label for="exampleInputEmail3">ID da Venda</label>
                <div class='input-group'>
                    <input type="text" class="form-control" id="id_venda">
            </div>

  </div>
  <div class="form-group col-sm-12">
    <label for="exampleInputEmail3"><span class="label label-default">Por Período</span></label>
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
    <label for="exampleInputEmail3"><span class="label label-default">Por RH</span></label>
  </div>
  <div class="form-group col-sm-4">
    <label for="exampleInputPassword3">Vendedor:</label>
    <select class="form-control select2" id="vendedor_id">
        <option value="">Todos</option>
        @foreach($vendedores as $key => $val)
          <option value={{$key}}> {{$val}}</option>
        @endforeach
    </select>
  </div>
  <div class="form-group col-sm-4">
    <label for="exampleInputPassword3">Cliente:</label>
    <select class="form-control select2" id="cliente_id">
        <option value="">Todos</option>
        @foreach($clientes as $key => $val)
          <option value={{$key}}> {{$val}}</option>
        @endforeach
    </select>
  </div>
  <div class="form-group col-sm-12">
    <label for="exampleInputEmail3"><span class="label label-default">Por Especificidades</span></label>
  </div>
  <div class="form-group col-sm-4">
    <label for="status">Status:</label>
    <select id="status" class="form-control">
        <option value="">Todos os status</option>
        <option value="aberta">Aberta</option>
        <option value="fechada">Fechada</option>
        <option value="cancelada">Cancelada</option>
    </select>
  </div>
  <div class="form-group col-sm-4">
    <label for="com_nota">Nota:</label>
    <select id="com_nota" class="form-control">
        <option value="-1">Todos</option>
        <option value="1">Contém</option>
        <option value="0">Não contém</option>
    </select>
  </div>
  <div class="form-group col-sm-12">
    <button type="button" url="{{url('/dopdf/gerar4.php')}}" class="btn btn-primary pull-right gerarpdfvendas"><span class="glyphicon glyphicon-file"></span> GERAR PDF</button>
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
