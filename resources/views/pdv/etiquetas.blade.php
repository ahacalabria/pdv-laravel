@extends('layouts.padrao')

@section('content')

<div class="page-header vendas">
  <h1>Etiquetas <small>novas etiquetas</small></h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif


<form class="form" action="dopdf/gerar6.php" method="get">

<div class="col-sm-12 barrinha-prod">
  <div class="form-group col-sm-2">
      {!! Form::label('Código') !!}
      <input class="form-control produtos_codigo_only" type="text">
  </div>
  <div class="form-group col-sm-8">
      {!! Form::label('Descrição produto') !!}
      <select class="form-control produtos_descricao_only"></select>
  </div>
  <div class="form-group col-sm-2">
    {!! Form::label('Ação') !!}
      <br><button class="btn btn-primary inserir_item" data-etiquetas="true" type="button"><span class="glyphicon glyphicon-plus"></span> INSERIR ITEM</button>
  </div>
</div>
<div class="col-sm-12">
  <table id="table_produtos" class="table table-striped" style="height:400px;display: -moz-groupbox;">
    <thead>
      <td>Cód</td>
      <td>Detalhes do Item</td>
      <td>Quantidade</td>
      <!-- <td>Valor</td> -->
      <!-- <td>Subtotal</td> -->
      <td></td>
    </thead>
  <tbody style="overflow-y: scroll;height: 300px;width: 100%;position: absolute;">
    <!-- <tr>
      <td class="col-sm-1">
        <input class="form-control" name="codigo_produto" type="text" disabled="true">
      </td>
      <td class="col-sm-4"></td>
      <td class="col-sm-2">
        <div class="input-group">
          <input type="text" class="form-control dinheiro quantidades" placeholder="0,00">
          <span class="input-group-addon"> </span>
        </div>
      </td>
      <td class="col-sm-2">
        <div class="input-group">
          <span class="input-group-addon">R$</span>
          <input type="text" class="form-control dinheiro valor_item" placeholder="0,00">
        </div>
      </td>
      <td class="col-sm-2 subtotal">
        <div class="input-group">
          <span class="input-group-addon">R$</span>
          <input id="teste_centavos" type="text" class="form-control dinheiro" placeholder="0,00" disabled="true">
        </div>
      </td>
      <td class="col-sm-1"><button class="btn btn-danger delete-row-produto" type="button"><span class="glyphicon glyphicon-remove"></span></button></td>
    </tr> -->
  </tbody>
</table>
</div>
<div class="form-group col-sm-8">
  <p>
    *Número de etiquetas por página: 20
  </p>
</div>
<div class="form-group col-sm-4 hidden">
  <div class="form-group">
    <label class="col-sm-7" for="">Valor Total:</label>
    <div class="input-group col-sm-5">
      <span class="input-group-addon">R$</span>
      <input id="valor_total_venda" type="text" class="form-control dinheiro" placeholder="0,00" disabled value="0.00">
    </div>
  </div>

</div>
</div>
<div class="form-group">
    <button id="confirmarVendaBt2" class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-ok"></span> GERAR PDF</button>
    <a id="cancelar_venda_nova" href="/" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> CANCELAR</a>
</div>
<div style="height:300px;"></div>

<!-- Modal -->
<div class="modal fade" id="modalConfirmarVenda" tabindex="-1" role="dialog" aria-labelledby="Detalhes da Venda">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detalhes da Venda</h4>
      </div>
      <div id="modal-conteudo" class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar Detalhes</button>
        <button id="finalizarVenda" type="button" class="btn btn-primary">Concluir Venda</button>
      </div>
    </div>
  </div>
</div>

{!! Form::close() !!}

@endsection
