@extends('layouts.padrao')

@section('content')
<div class="page-header">
  <h1>Caixa <small>contas a pagar</small></h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif

{{ Form::model($financeiropagar, array('route' => array('financeiropagar.update', $financeiropagar->id), 'class' => 'form' , 'method' => 'PUT')) }}

<div class="col-sm-12">
<div class="form-group col-sm-3">
    {!! Form::label('Título da conta') !!}
      <input class="form-control" type="text" name="titulo" value="{{$financeiropagar->titulo}}"/>
</div>
<div class="form-group col-sm-6">
    {!! Form::label('Descrição da conta') !!}
    <input id="" class="form-control" type="text" name="descricao" value="{{$financeiropagar->descricao}}"/>
</div>
<div class="form-group col-sm-3">
    {!! Form::label('Valor da conta') !!}
    <div class="input-group col-sm-12">
      <span class="input-group-addon">RS</span>
      <input id="valor_total_liquido" class="form-control dinheiro" type="text" value="{{$financeiropagar->valor_total}}"/>
    </div>
</div>
  <div class="form-group">
    {!! Form::label('Tipo pagamento') !!}
    <div class="input-group col-sm-2">
      <span class="input-group-addon"><span class="glyphicon glyphicon-credit-card"></span></span>
      <select id="tipopagamentos" class="form-control" name="tipo_pagamento_id">
        @foreach($tipopagamentos as $tipopagamento)
          <option value={{ $tipopagamento->id }}>{{ $tipopagamento->tipo }}</option>
          @endforeach
      </select>

    </div>
  </div>

  <div class="panel panel-default">
  <div class="panel-heading">Condições de pagamento</div>
  <div class="panel-body">
    <div class="col-sm-12">
    <div class="form-group col-sm-3">
        {!! Form::label('Qtd. Parcelas') !!}
        <div class="form-group">
          <select class="col-sm-3 form-control" name="quantidade_parcelas" id="parcela">
          </select>
        </div>
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('Juros') !!}
        <select class="form-control" name="juros" id="parcela">
          <option value="semjuros" selected>Sem Juros</option>
          <option value="simples">Juros Simples</option>
        </select>
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('Valor do juros') !!}
        <div class="input-group col-sm-12">
          <span class="input-group-addon">%</span>
          <input id="valor-juros" class="form-control dinheiro" type="text" name="valor_juro_aplicado"/>
        </div>
    </div>
    <div class="form-group col-sm-3">
        {!! Form::label('Dia do vecimento') !!}
        <div class="input-group col-sm-12">
          <span class="input-group-addon"><span class="glyphicon glyphicon-pushpin"></span></span>
          <input id="dia_vencimento" class="form-control" type="text" name="dia_vencimento" value="{{date('d')}}"/>
        </div>
    </div>
    </div>
    <div id="condicao_pagamento_valor" class="col-sm-12"><!-- tipopagamento --></div>
    <div id="parcelas-content">
      <!--  parcelas criadas dinamicamente -->
    </div>
    <hr style="clear:both;" />
    <div class="form-group col-sm-4">
        {!! Form::label('Pessoa responsável pelo pagamento') !!}
        <div class="input-group col-sm-12">
          <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
          <input id="pessoa_recebeu_id" class="form-control" type="text" disabled value="{{ Auth::user()->name }}"/>
          <input type="hidden" value="{{ Auth::user()->pessoa_id }}" name="pessoa_recebeu_id">
          <span class="input-group-addon">#{{ Auth::user()->pessoa_id }}</span>
        </div>
    </div>
    <div class="form-group col-sm-4" id="div-valor-recebido">
        {!! Form::label('Valor pago') !!}
        <div class="input-group col-sm-12">
          <span class="input-group-addon">R$ </span>
          <input id="valor_pago" class="form-control dinheiro" type="text" value="0.00"/>
          <input id="valor_pago_hidden" type="hidden" name="valor_pago"/>
        </div>
    </div>
    <div class="form-group col-sm-4" id="div-valor-troco">
        {!! Form::label('Troco') !!}
        <div class="input-group col-sm-12">
          <span class="input-group-addon">R$ </span>
          <input id="valor_troco" class="form-control dinheiro" type="text" disabled value=""/>
          <input id="valor_troco_hidden" type="hidden" name="valor_troco"/>
        </div>
    </div>
    </div>
<!-- </div> -->

<div class="form-group col-md-12">
<br>
    <input id="valor_total_hidden" type="hidden" name="valor_total"/>
    <input id="todasasparcelas" type="hidden" name="todasasparcelas">
    <input type="hidden" name="quantidade_parcelas_pagas" value="1">
    <input type="hidden" name="pessoa_recebeu_id" value="">
    <input type="hidden" name="venda_id" value="">
    <input type="hidden" name="venda_status" value="fechada">
    <input type="hidden" name="status" value="pago">
    <button id="pagarcontaconfirm" class="btn btn-primary" type="button"><span class="glyphicon glyphicon-ok"></span> SALVAR CONTA</button>
    <a href="/financeiro" class="btn btn-default pull-right"><span class="glyphicon glyphicon-remove"></span> CANCELAR</a>
</div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalReceberVenda" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div id="modal-conteudo" class="row">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button id="salvar-cheque" url="/vendas" type="button" class="btn btn-primary salvar-cheque">Concluir</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalPagarContaConfirmar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal-title2"></h4>
      </div>
      <div class="modal-body">
        <div id="modal-conteudo2" class="row">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button id="pagarconta" type="button" class="btn btn-primary">Concluir</button>
      </div>
    </div>
  </div>
</div>

{!! Form::close() !!}

@endsection
