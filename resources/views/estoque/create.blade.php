@extends('layouts.padrao')

@section('content')

<div class="page-header">
  <h1>Entrada de Produtos <small>nova nota</small></h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif


{!! Form::open(array('route' => 'estoque.store', 'class' => 'form', 'id' => 'formulario')) !!}

<div class="form-group col-sm-3">
  {!! Form::label('Forncedor') !!}
  <select class="form-control js-example-basic-single js-example-responsive" id="fornecedores" name="pessoa_fornecedor_id"></select>
  <a href="../pessoa/create?tipo=fornecedor"> Fornecedor ainda não cadastrado?</a>
</div>
<div class="form-group col-sm-3">
    {!! Form::label('Código da Nota') !!}
    <input id="codigo" class="form-control" type="text" name="codigo_nota"/>
</div>
<div class="form-group col-sm-3">
    {!! Form::label('Data da Emissão') !!}
    <div class="form-group">
                <div class='input-group date datetimepicker-now'>
                    <input id="data_emissao_nota" name="data_emissao" type='text' class="form-control"/>
                    <span class="input-group-addon btn">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
              </div>
    </div>
</div>
<div class="form-group col-sm-3">
    {!! Form::label('Data da Entrada') !!}
    <div class="form-group">
                <div class='input-group date' id='datetimepicker'>
                    <input id="data_entrada_nota" name="data_entrada" type='text' class="form-control"/>
                    <span class="input-group-addon btn">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
              </div>
    </div>
</div>
<div class="col-sm-12">
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" data-parent="" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            Dados do Pruduto #1
          </a>
        </h4>
      </div>
      <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
          <div class="col-sm-12">
            <div class="form-group col-sm-6 col-md-12">
              <label>Produto existente</label>
              <select id="produtos1" linha="1" name="produto_id[]" class="form-control js-example-basic-single produtos produto-nota"></select>
            </div>
          <div class="form-group col-sm-4">
            <label>Código do Produto</label>
              <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span></span>
                <input id="codigo1" class="form-control" type="text" name="codigo[]" placeholder='Informe um codigo para o produto'>
              </div>
          </div>
          <div class="form-group col-sm-4">
            <label>Codigo NCM</label>
              <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span></span>
                <input id="codigo_ncm1" class="form-control" type="text" name="codigo_ncm[]" placeholder='Informe um codigo ncm para o produto'>
              </div>
          </div>
          <div class="form-group col-sm-4">
            <label>Título</label>
              <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-font" aria-hidden="true"></span></span>
                <input id="titulo1" class="form-control" type="text" name="titulo[]" placeholder='Informe um título para o produto'>
              </div>
          </div>
          </div>
          <div class="col-sm-12">
          <div class="form-group col-sm-4">
            <label>Unidade de Medida</label>
              <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-glass" aria-hidden="true"></span></span>
                <select class="form-control" id="unidades1" name="unidade_id[]"></select>
              </div>
              <a href="../unidades/create"> Unidade de medida ainda não cadastrada?</a>
          </div>
          <div class="form-group col-sm-4">
              <label>Quantidade</label>
              <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span></span>
                <input id="quantidade1" name="quantidade_estoque[]" type="number" class="form-control" aria-label="Quantidade em estoque" placeholder="0">
              </div>
          </div>
          <div class="form-group col-sm-4">
              <label>Preço de compra</label>
              <div class="input-group">
                <span class="input-group-addon">R$</span>
                <input id="custo1" type="text" class="form-control dinheiro" name="custo[]" aria-label="Custo do produto" placeholder="0,00">
                <input id="custo_produto1" type="hidden" name="custo_produto[]">
              </div>
          </div>
          </div>
          <div class="col-sm-12">
          <div class="form-group col-sm-4">
            <label>Despesas sobre o Produto</label>
              <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></span></span>
                <select class="form-control" id="impostos1"></select>
                <span id="addimposto" linha="1" class="btn input-group-addon btn-primary addimposto-nota"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></span>
              </div>
                <a href="../impostos/create"> Despesa ainda não cadastrada?</a>
              <input id="impostos_id1" name="impostos_id[]" type="text" class="impostos_id1 hidden">
            <div id="imposto-lista1" class="form-group"><div class="selecionarimpostos" id="selecionarimpostos1"></div></div>
          </div>
          <div class="form-group col-sm-4 hidden">
              <label>Frete</label>
              <div class="input-group">
                <span class="input-group-addon">R$</span>
                <input id="frete1" type="text" name="frete[]" class="form-control dinheiro" aria-label="Frete do produto" value=0>
                <input id="frete_produto1" name="frete_produto[]" type="hidden">
              </div>
          </div>
          <div class="form-group col-sm-4">
              <label>Preço de Venda</label>
              <div class="input-group">
                <span class="input-group-addon">R$</span>
                <input id="preco1" type="text" class="form-control dinheiro" name="preco[]" aria-label="Valor do produto" placeholder="0,00">
                <input id="preco_produto1" type="hidden" name="preco_produto[]">
              </div>
          </div>
          <div class="form-group col-sm-12">
            <label>Descrição</label>
            <textarea id="descricao1" name="descricao[]" class="form-control" placeholder="Informe alguma descrição para o produto"></textarea>
          </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<div class="form-group col-sm-8">
  <button id="add_produto_row_nota" type="button" class="btn btn-success" name="button"><span class="glyphicon glyphicon-plus"></span> Adicionar produto</button>
</div>
<div class="form-group col-sm-4">

  <div class="form-group">
    <label class="col-sm-7" for="">Valor frete:</label>
    <div class="input-group col-sm-5">
      <span class="input-group-addon">R$</span>
      <input id="valor_frete_nota" type="text" class="form-control dinheiro" placeholder="0,00" value="0.00">
      <input id="valor_frete_nota_hidden" type="hidden" name="valor_frete_nota">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-7" for="">Valor total:</label>
    <div class="input-group col-sm-5">
      <span class="input-group-addon">R$</span>
      <input id="valor_total_nota" type="text" class="form-control dinheiro" placeholder="0,00" value="0.00">
      <input id="valor_total_nota_hidden" type="hidden" name="valor_total_nota">
    </div>
  </div>
</div>
<div class="form-group">
    <!-- <input id="data_venda_hidden" type="hidden" name="data_venda">
    <input id="produtos_id" type="hidden" name="produtos_id"/>
    <input id="quantidades" type="hidden" name="quantidades"/>
    <input id="valor_total_hidden" type="hidden" name="valor_total">
    <input id="valor_desconto_hidden" type="hidden" name="valor_desconto">
    <input id="valor_frete_hidden" type="hidden" name="valor_frete">
    <input id="valor_total_liquido_hidden" type="hidden" name="valor_liquido">
    <input type="hidden" name="status" value="aberta"> -->
    <!-- <button id="confirmarVendaBt" class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalConfirmarVenda"><span class="glyphicon glyphicon-ok"></span> CONFIRMAR NOTA</button> -->
    <button class="btn btn-primary criar-produtos-nota" type="button"><span class="glyphicon glyphicon-ok"></span> CONFIRMAR NOTA</button>
    <a href="/estoque" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> CANCELAR</a>
</div>

<!-- Modal -->
<!-- <div class="modal fade" id="modalConfirmarVenda" tabindex="-1" role="dialog" aria-labelledby="Detalhes da Venda">
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
</div> -->

{!! Form::close() !!}

@endsection
