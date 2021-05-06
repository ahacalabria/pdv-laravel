@extends('layouts.padrao')

@section('content')

<div class="page-header vendas">
  <h1>PDV <small>nova venda</small></h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif


{!! Form::open(array('route' => 'vendas.store', 'class' => 'form ppt', 'id' => 'formulario')) !!}

<div class="form-group  col-lg-4 col-md-4 col-sm-12">
  {!! Form::label('Cliente') !!}
  <select class="form-control select_dynamic" id="clientes" name="pessoa_cliente_id" data-endpoint="/getpessoatipo/pessoa"></select>
</div>
<div class="form-group col-lg-3 col-md-3 col-sm-6">
    {!! Form::label('Vendedor') !!}
    <input id="vendedor_nome" class="form-control" type="text" readonly="readonly" name="pessoa_vendedor_id" required value="{{ Auth::user()->name }}"/>
    <input type="hidden" value="{{ Auth::user()->pessoa_id }}" name="pessoa_vendedor_id">
</div>
<div class="form-group col-lg-2 col-md-2 col-sm-2">
    {!! Form::label('Cód Venda') !!}
    <input class="form-control" type="text" readonly name="codigo" required placeholder=''/>
</div>
<div class="form-group col-lg-3 col-md-3 col-sm-4">
    {!! Form::label('Data da Venda') !!}
    <div class="form-group">
                <div class='input-group date' id='datetimepicker'>
                    <input id="data_venda" type='text' class="form-control"/>
                    <span class="input-group-addon btn">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
</div>
<div class="col-sm-12 barrinha-prod">
  <div class="form-group col-sm-2 colunaa1">
      {!! Form::label('Código') !!}
      <input class="form-control produtos_codigo_only" placeholder="CÓD. PRODUTO" type="text">
  </div>
  <div class="form-group col-sm-8 colunab2">
      {!! Form::label('Descrição produto') !!}
      <select class="form-control produtos_descricao_only"></select>
  </div>
  <div class="form-group col-sm-2 colunac3">
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
  <!-- <button id="add_produto_row" type="button" class="btn btn-success" name="button"><span class="glyphicon glyphicon-plus"></span> Adicionar produto</button> -->
  <!-- <div class="form-group col-sm-6">
    <div class="form-group">
      <label class="col-sm-7" for="">Total de Itens:</label>
      <div class="col-sm-5">
        <input id="total_itens" type="text" class="form-control" disabled>
      </div>
    </div>
  </div> -->
</div>
<div class="form-group col-sm-4">
  <div class="form-group">
    <label class="col-sm-7" for="">Valor Total:</label>
    <div class="input-group col-sm-5">
      <span class="input-group-addon">R$</span>
      <input id="valor_total_venda" type="text" class="form-control dinheiro" placeholder="0,00" disabled value="0.00">
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
      <input id="valor_desconto" type="text" class="form-control dinheiro" placeholder="0,00" value="0.00">
      <input type="hidden" name="tipo_desconto" id="tipo_desconto" value="d">
    </div>
  </div>
  <div class="form-group hidden">
    <label class="col-sm-7" for="">Frete a cobrar do cliente:</label>
    <div class="input-group col-sm-5">
      <span class="input-group-addon">R$</span>
      <input id="valor_frete" type="text" class="form-control dinheiro" placeholder="0,00" value="0.00">
    </div>
  </div>
  <div class="form-group hidden">
    <label class="col-sm-7" for=""><b>TOTAL LÍQUIDO:</b></label>
    <div class="input-group col-sm-5">
      <span class="input-group-addon">R$</span>
      <input id="valor_total_liquido" type="text" class="form-control dinheiro" placeholder="0,00" disabled value="0.00">
    </div>
  </div>
  <div class="form-group hidden">
    <label class="col-sm-7" for=""><b>Tipo Pagamento:</b></label>
    <div class="input-group col-sm-5">
      <span class="input-group-addon"><span class="glyphicon glyphicon-credit-card"></span></span>
      <select id="tipopagamentos" class="form-control tipopagamentos" name="tipo_pagamento_id">
      </select>
    </div>
  </div>

</div>
</div>
<div class="form-group col-sm-12">
  <div class="checkbox">
    <label>
      <input id="sel_conferente" type="checkbox"> Selecionar conferente
    </label>
  </div>
</div>
<div class="col-sm-12">
  <div class="form-group col-sm-4">
  {!! Form::label('Conferente') !!}
  <select readonly class="form-control js-example-basic-single js-example-responsive" id="funcionarios" name="pessoa_conferente_id"></select>
  <a href="../pessoa/create?tipo=funcionario"> Funcionário ainda não cadastrado?</a>
</div>
</div>
<div class="form-group">
    <input id="data_venda_hidden" type="hidden" name="data_venda">
    <input id="produtos_id" type="hidden" name="produtos_id"/>
    <input id="quantidades" type="hidden" name="quantidades"/>
    <input id="precos" type="hidden" name="precos"/>
    <input id="valor_total_hidden" type="hidden" name="valor_total">
    <input id="valor_desconto_hidden" type="hidden" name="valor_desconto">
    <input id="valor_frete_hidden" type="hidden" name="valor_frete">
    <input id="valor_total_liquido_hidden" type="hidden" name="valor_liquido">
    <input type="hidden" name="status" value="aberta">
    <button id="confirmarVendaBt" class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalConfirmarVenda"><span class="glyphicon glyphicon-ok"></span> CONFIRMAR VENDA</button>
    <a id="cancelar_venda_nova" href="/vendas" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> CANCELAR</a>
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
