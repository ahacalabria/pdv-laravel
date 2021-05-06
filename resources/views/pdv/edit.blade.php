@extends('layouts.padrao')

@section('content')
<div class="page-header vendas">
  <h1>PDV <small>editando venda #{{$venda->id}}</small></h1>
</div>
@if (Session::has('message'))
    <!-- <div class="alert alert-info">{{ Session::get('message') }}</div> -->
       <div class="alert alert-info alert-dismissible" role="alert">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <strong>Informação do Sistema: </strong>{{ Session::get('message') }}
       </div>
@endif
{{ Form::model($venda, array('route' => array('vendas.update', $venda->id), 'class' => 'form' , 'id' => 'formulario' , 'method' => 'PUT')) }}

<div class="form-group col-sm-5">
  <!-- <select class="form-control js-example-basic-single js-example-responsive" id="clientes" name="pessoa_cliente_id"></select> -->
  {!! Form::label('Cliente') !!}
  <select id="cliente_nome_sel" class="form-control" name="" readonly="readonly">
    @foreach($clientes as $cliente)
      <option value={{ $cliente->id }} @if($cliente->id == $venda->pessoa_cliente_id ) selected="selected" @endif >{{ $cliente->nome . $cliente->razao_social }}</option>
      @endforeach
  </select>
  <input type="hidden" value="{{ $venda->pessoa_cliente_id }}" name="pessoa_cliente_id">
</div>
<div class="form-group col-sm-2">
    {!! Form::label('Vendedor') !!}
    <input id="vendedor_nome" class="form-control" type="text" readonly="readonly" required value="{{ $venda->vendedor->nome }}"/>
    <input type="hidden" value="{{ $venda->vendedor->id }}" name="pessoa_vendedor_id">
</div>
<div class="form-group col-sm-2">
    {!! Form::label('Cód Venda') !!}
    <input class="form-control" type="text" readonly name="codigo" value="{{$venda->id}}"/>
</div>
<div class="form-group col-sm-3">
    {!! Form::label('Data da Venda') !!}
    <div class="form-group">
                <div class='input-group date' id='datetimepickerEDIT'>
                    <input id="data_venda" type='text' class="form-control" name="data_venda" value="{{ date('d/m/Y H:i', strtotime($venda->data_venda)) }}"/>
                    <span class="input-group-addon btn">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
</div>
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
      <br><button class="btn btn-primary inserir_item" type="button"><span class="glyphicon glyphicon-plus"></span> INSERIR ITEM</button>
  </div>
</div>

<div class="col-sm-12">
  <table id="table_produtos" class="table table-striped" style="height:400px;display: -moz-groupbox;">
    <thead>
      <td>Cód</td>
      <td>Detalhes do Item</td>
      <td>Quantidade</td>
      <td>Valor</td>
      <td>Subtotal</td>
      <td></td>
    </thead>
  <tbody style="overflow-y: scroll;height: 300px;width: 100%;position: absolute;">
    @foreach($venda->produtos as $produto)

    <tr>
      <td class="col-sm-1">
        <input type="text" class="form-control" disabled value="{{$produto->id}}" prodid="{{$produto->id}}">
      </td>
      <td class="col-sm-4">{{$produto->titulo}}</td>
      <td class="col-sm-2">
        <div class="input-group">
          <input type="text" class="form-control dinheiro quantidades" value="{{$produto->pivot->quantidade}}" placeholder="0,00">
          <span class="input-group-addon">{{$produto->unidade->sigla}}</span>
        </div>
      </td>
      <td class="col-sm-2">
        <div class="input-group">
          <span class="input-group-addon">R$</span>
          <input type="text" class="form-control dinheiro valor_item" @if(Auth::user()->level=="vendedor") {{"readonly='readonly'"}} @endif placeholder="0,00" value={{ $produto->pivot->preco }}>
        </div>
      </td>
      <td class="col-sm-2 subtotal">
        <div class="input-group">
          <span class="input-group-addon">R$</span>
          <input id="teste_centavos" type="text" class="form-control dinheiro" placeholder="0,00" disabled value="{{ $produto->pivot->subtotal }}">
        </div>
      </td>
      <td class="col-sm-1"><button class="btn btn-danger delete-row-produto" type="button"><span class="glyphicon glyphicon-remove"></span></button></td>
    </tr>
@endforeach
  </tbody>
</table>
</div>
<div class="form-group col-sm-8">
  <!-- <button id="add_produto_row" type="button" class="btn btn-success" name="button"><span class="glyphicon glyphicon-plus"></span> Adicionar produto</button> -->
</div>
<div class="form-group col-sm-4">
  <div class="form-group">
    <label class="col-sm-7" for="">Valor Total:</label>
    <div class="input-group col-sm-5">
      <span class="input-group-addon">R$</span>
      <input id="valor_total_venda" type="text" class="form-control dinheiro" placeholder="0,00" disabled value="{{$venda->valor_total}}">
    </div>
  </div>
  <div class="form-group hidden">
    <label class="col-sm-5" for="">Desconto:</label>
    <div class="input-group col-sm-7">
      <div class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tipo <span class="caret"></span></button>
        <ul class="dropdown-menu">
          <li><a id="bt_tipo_desconto_d">R$</a></li>
          <li><a id="bt_tipo_desconto_p">%</a></li>
        </ul>
      </div>
      <span id="span_tipo_desconto" class="input-group-addon" id="basic-addon1">R$</span>
      <input id="valor_desconto" type="text" class="form-control dinheiro" placeholder="0,00" value="{{$venda->valor_desconto}}">
      <input type="hidden" name="tipo_desconto" id="tipo_desconto" value="d">
    </div>
  </div>
  <div class="form-group hidden">
    <label class="col-sm-7" for="">Frete a cobrar do cliente:</label>
    <div class="input-group col-sm-5">
      <span class="input-group-addon">R$</span>
      <input id="valor_frete" type="text" class="form-control dinheiro" placeholder="0,00" value="{{$venda->valor_frete}}">
    </div>
  </div>
  <div class="form-group hidden">
    <label class="col-sm-7" for=""><b>TOTAL LÍQUIDO:</b></label>
    <div class="input-group col-sm-5">
      <span class="input-group-addon">R$</span>
      <input id="valor_total_liquido" type="text" class="form-control dinheiro" placeholder="0,00" disabled value="{{$venda->valor_liquido}}">
    </div>
  </div>
  <div class="form-group hidden">
    <label class="col-sm-7" for=""><b>Tipo Pagamento:</b></label>
    <div class="input-group col-sm-5">
      <span class="input-group-addon"><span class="glyphicon glyphicon-credit-card"></span></span>
      <select id="tipopagamentos_edit_venda" class="form-control" name="tipo_pagamento_id">
        @foreach($tipopagamentos as $tipopagamento)
          <option value={{ $tipopagamento->id }} @if($tipopagamento->id == $venda->tipo_pagamento_id ) selected="selected" @endif >{{ $tipopagamento->tipo }}</option>
          @endforeach
      </select>
    </div>
  </div>
</div>
<div class="col-sm-12">
<div class="form-group col-sm-4">
  {!! Form::label('Conferente') !!}
  <select class="form-control js-example-basic-single js-example-responsive" name="pessoa_conferente_id">
    @foreach($funcionarios as $funcionario)
      <option value={{ $funcionario->id }} @if($funcionario->id == $venda->pessoa_conferente_id ) selected="selected" @endif >{{ $funcionario->nome }}</option>
    @endforeach
  </select>
  <!-- <a href="../pessoa/create?tipo=funcionario"> Funcionário ainda não cadastrado?</a> -->
</div>
</div>
<div class="col-sm-12 form-group">
    <input id="data_venda_hidden" type="hidden" name="">
    <input id="produtos_id" type="hidden" name="produtos_id"/>
    <input id="quantidades" type="hidden" name="quantidades"/>
    <input id="precos" type="hidden" name="precos"/>
    <input id="valor_total_hidden" type="hidden" name="valor_total">
    <input id="valor_desconto_hidden" type="hidden" name="valor_desconto">
    <input id="valor_frete_hidden" type="hidden" name="valor_frete">
    <input id="valor_total_liquido_hidden" type="hidden" name="valor_liquido">
    <input type="hidden" name="status" value="aberta">
    <button id="confirmarVendaBt" class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalConfirmarVenda"><span class="glyphicon glyphicon-ok"></span> CONFIRMAR VENDA</button>
    <a href="/vendas" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> CANCELAR</a>
</div>
<div style="clear:both"></div>
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
