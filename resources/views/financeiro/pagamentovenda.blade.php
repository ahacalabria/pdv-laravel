@extends('layouts.padrao')

@section('content')
<div class="page-header">
  <h1>Caixa <small>pagamento de vendas</small></h1>
</div>
@if(Session::has('message'))
 <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Sucesso!</strong> {{ Session::get('message') }}
      </div>
@endif
<!-- {{ Form::model($venda, array('route' => array('vendas.update', $venda->id), 'class' => 'form' , 'method' => 'PUT')) }} -->
{!! Form::open(array('route' => 'financeiroreceber.store', 'class' => 'form', 'id'=>'formulario')) !!}


<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          Detalhes da venda #{{$venda->id}}<span class="glyphicon glyphicon-option-vertical pull-right"></span>
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">

        <div class="form-group col-sm-5">
          {!! Form::label('Cliente') !!}
            <input type="text" class="form-control" disabled value="{{ $venda->cliente->nome.$venda->cliente->razao_social }}">
        </div>
        <div class="form-group col-sm-2">
            {!! Form::label('Vendedor') !!}
            <input id="vendedor_nome" class="form-control" type="text" disabled value="{{ $venda->vendedor->nome }}"/>
            <input type="hidden" value="{{ $venda->vendedor->id }}">
        </div>
        <div class="form-group col-sm-2">
            {!! Form::label('Código da Venda') !!}
            <input class="form-control" type="text" readonly value="{{$venda->id}}"/>
        </div>
        <div class="form-group col-sm-3">
            {!! Form::label('Data da Venda') !!}
            <div class="form-group">
                            <input id="data_venda" type='text' disabled class="form-control" value="{{ date('d/m/Y H:i', strtotime($venda->data_venda)) }}"/>
                    </div>
        </div>
        <div class="col-sm-12">
          <table id="table_produtos" class="table table-striped">
            <thead>
              <td>Produto</td>
              <td>Detalhes do Item</td>
              <td>Quantidade</td>
              <td>Valor</td>
              <td>Subtotal</td>
            </thead>
          <tbody>
            @foreach($venda->produtos as $produto)
            <tr>
              <td class="col-sm-3">
                <input type="text" class="form-control" disabled value="{{$produto->titulo}}" prodid="{{$produto->id}}">
              </td>
              <td class="col-sm-2">{{$produto->descricao}}</td>
              <td class="col-sm-2">
                <div class="input-group">
                  <input type="text" class="form-control dinheiro quantidades" disabled value="{{$produto->pivot->quantidade}}" placeholder="0,00">
                  <span class="input-group-addon">{{$produto->unidade->sigla}}</span>
                </div>
              </td>
              <td class="col-sm-2">
                <div class="input-group">
                  <span class="input-group-addon">R$</span>
                  <input type="text" class="form-control dinheiro" placeholder="0,00" disabled="false" value={{ $produto->pivot->preco }}>
                </div>
              </td>
              <td class="col-sm-2 subtotal">
                <div class="input-group">
                  <span class="input-group-addon">R$</span>
                  <input id="teste_centavos" type="text" class="form-control dinheiro" placeholder="0,00" disabled value="{{ $produto->pivot->subtotal }}">
                </div>
              </td>
            </tr>
        @endforeach
          </tbody>
        </table>
        </div>
        <div class="form-group col-sm-4">
          <div class="form-group">
            <label class="col-sm-7" for="">Valor Total:</label>
            <div class="input-group col-sm-5">
              <span class="input-group-addon">R$</span>
              <input id="valor_total_venda" type="text" class="form-control dinheiro" placeholder="0,00" disabled value="{{$venda->valor_total}}">
            </div>
          </div>
          <!-- <div class="form-group">
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
              <input type="hidden" id="tipo_desconto" value="d">
            </div>
          </div> -->
          <div class="form-group hidden">
            <label class="col-sm-7" for="">Frete a cobrar do cliente:</label>
            <div class="input-group col-sm-5">
              <span class="input-group-addon">R$</span>
              <input id="valor_frete" type="text" class="form-control dinheiro" placeholder="0,00" value="{{$venda->valor_frete}}">
            </div>
          </div>
      </div>
          <div class="pull-right"><a href="/vendas/{{$venda->id}}/edit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span> Editar Venda</a></div>
    </div>
  </div>
</div>
<br>
<div class="form-group">
  <label class="col-sm-2" for="">Desconto:</label>
  <div class="input-group col-sm-4">
    <div class="input-group-btn">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tipo <span class="caret"></span></button>
      <ul class="dropdown-menu">
        <li><a id="bt_tipo_desconto_d">R$</a></li>
        <li><a id="bt_tipo_desconto_p">%</a></li>
      </ul>
    </div>
    <span id="span_tipo_desconto" class="input-group-addon" id="basic-addon1">R$</span>
    <input id="valor_desconto" type="text" class="form-control dinheiro total_venda_load_pg" placeholder="0,00" value="{{$venda->valor_desconto}}">
    <input id="valor_desconto_hidden" type="hidden" name="valor_desconto">
    <input type="hidden" id="tipo_desconto" name="tipo_desconto" value="d">
  </div>
</div>
  <div class="form-group">
    <label class="col-sm-2" for=""><b>Total da venda:</b></label>
    <div class="input-group col-sm-4">
      <span class="input-group-addon">R$</span>
      <input id="valor_total_liquido" type="text" class="form-control dinheiro" placeholder="0,00" disabled value="{{$venda->valor_liquido}}">
      <input type="hidden" id="valor_total_liquido_hidden" name="valor_liquido"/>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-2" for=""><b>Tipo Pagamento:</b></label>
    <div class="input-group col-sm-4">
      <span class="input-group-addon"><span class="glyphicon glyphicon-credit-card"></span></span>
      <select id="tipopagamentos" class="form-control" name="tipo_pagamento_id">
        @foreach($tipopagamentos as $tipopagamento)
          <option value={{ $tipopagamento->id }} @if($tipopagamento->id == $venda->tipo_pagamento_id ) selected="selected" @endif >{{ $tipopagamento->tipo }}</option>
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
        {!! Form::label('Pessoa responsável pelo recebimento') !!}
        <div class="input-group col-sm-12">
          <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
          <input id="pessoa_recebeu_id" class="form-control" type="text" disabled value="{{ Auth::user()->name }}"/>
          <input type="hidden" value="{{ Auth::user()->pessoa_id }}" name="pessoa_recebeu_id">
          <span class="input-group-addon">#{{ Auth::user()->pessoa_id }}</span>
        </div>
    </div>
    <div class="form-group col-sm-4" id="div-valor-recebido">
        {!! Form::label('Valor recebido') !!}
        <div class="input-group col-sm-12">
          <span class="input-group-addon">R$ </span>
          <input id="valor_recebido" class="form-control dinheiro" type="text"/>
          <input id="valor_recebido_hidden" type="hidden" name="valor_recebido"/>
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
</div>

</div>
<br>
<div class="form-group">
    <!-- <input id="data_venda_hidden" type="hidden" name=""> -->
    <!-- <input id="produtos_id" type="hidden" name="produtos_id"/> -->
    <input id="tipopagamentonome" type="hidden" name="tipopagamentonome"/>
    <input id="todasasparcelas" type="hidden" name="todasasparcelas">
    <input type="hidden" name="quantidade_parcelas_pagas" value="1">
    <input type="hidden" name="pessoa_recebeu_id" value="{{$venda->pessoa_vendedor_id}}">
    <input id="venda_id" type="hidden" name="venda_id" value="{{$venda->id}}">
    <input type="hidden" name="venda_status" value="fechada">
    <input type="hidden" name="status" value="pago">
    <button id="pagarvendaconfirm" class="btn btn-primary" type="button"><span class="glyphicon glyphicon-ok"></span> CONFIRMAR PAGAMENTO</button>
    <a href="/vendas" class="btn btn-default pull-right"><span class="glyphicon glyphicon-remove"></span> CANCELAR</a>
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
<div class="modal fade" id="modalReceberVendaConfirmar" tabindex="-1" role="dialog">
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
        <button id="pagarvenda" type="button" class="btn btn-primary">Concluir</button>
      </div>
    </div>
  </div>
</div>

{!! Form::close() !!}

@endsection
