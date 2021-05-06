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
  <h1>Relatório <small>Produtos Internos</small><button id="filtros_show" type="button" class="btn btn-default pull-right" style="display: none"><span class="glyphicon glyphicon-filter"></span> Voltar para os filtros</button></h1>
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
    <label for="exampleInputEmail3"><span class="label label-default">Por Especificidades</span></label>
  </div>
  <div class="form-group col-sm-4">
    <label for="com_nota">Com saldo em estoque superior a</label>
    <input id="qtd_estoque" type="text" class="form-control">
  </div>
  <div class="form-group col-sm-12">
    <button type="button" url="/dopdf/gerar.php" class="btn btn-primary pull-right gerarprodinter"><span class="glyphicon glyphicon-file"></span> GERAR PDF</button>
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
