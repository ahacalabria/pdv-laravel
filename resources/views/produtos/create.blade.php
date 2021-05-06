@extends('layouts.padrao')

@section('content')
<div class="page-header">
  <h1>Cadastro <small>Produto</small>
    <button class="btn btn-primary pull-right updatecampos"type="button"><span class="glyphicon glyphicon-refresh"></span> Atualizar campos</button>
  </h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif

{!! Form::open(array('route' => 'produtos.store', 'class' => 'form')) !!}

<div class="form-group">
    {!! Form::label('Fornecedor') !!}
      <a class="pull-right" href="../pessoa/create?tipo=fornecedor" target="_blank"> Fornecedor ainda não cadastrado?</a>
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
      <select class="form-control" id="fornecedores" name="pessoa_id" value="{{ old('pessoa_id', '') }}"></select>
    </div>
</div>
@if(!empty(old('pessoa_id', '')))
<script>
  var pessoa_id = {{old('pessoa_id', '')}};
  setTimeout(function(){
    $('#fornecedores').val(pessoa_id).trigger('change'); 
  },500);
</script>
@endif

<div class="form-group hidden">
    {!! Form::label('Código do Produto') !!}
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span></span>
      <input class="form-control" type="text" name="codigo" placeholder='Informe um codigo para o produto' value="{{rand()}}">
    </div>
</div>
<div class="form-group hidden">
    {!! Form::label('Codigo NCM do Produto') !!}
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span></span>
      <input class="form-control" type="text" name="codigo_ncm" placeholder='Informe um codigo ncm para o produto' value="{{rand()}}">
    </div>
</div>

<div class="form-group">
    {!! Form::label('Nome do Produto') !!}
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-font" aria-hidden="true"></span></span>
      <input class="form-control" type="text" name="titulo" placeholder='Informe um título para o produto' value="{{ old('titulo', '') }}">
    </div>
</div>
<div class="form-group">
  {!! Form::label('Categorias') !!}
  <a class="pull-right" href="../categorias/create" target="_blank"> Categoria ainda não cadastrada?</a>
  <div class="panel-group" id="categorias" role="tablist" aria-multiselectable="true"></div>
</div>
<div class="form-group">
    {!! Form::label('Unidade de Medida do Produto') !!}
    <a class="pull-right" href="../unidades/create" target="_blank"> Unidade de medida ainda não cadastrada?</a>
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-glass" aria-hidden="true"></span></span>
      <select class="form-control" id="unidades" name="unidade_id" value="{{ old('unidade_id', '') }}">
      </select>
    </div>
</div>
@if(!empty(old('unidade_id', '')))
<script>
  var unidade_id = {{old('unidade_id', '')}};
  setTimeout(function(){
    $('#unidades').val(unidade_id).trigger('change'); 
  },500);
</script>
@endif
<div class="form-group">
    {!! Form::label('Quantidade em Estoque') !!}
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span></span>
      <input id="quantidade_estoque" name="quantidade_estoque" type="text" class="form-control" aria-label="Quantidade em estoque" value="{{ old('quantidade_estoque', '') }}">
      <span id="spanUnidade" class="input-group-addon"> ? </span>
    </div>
</div>

<div class="form-group">
    {!! Form::label('Preço de Compra') !!}
    <div class="input-group">
      <span class="input-group-addon">R$</span>
      <input id="custo_produto" type="text" class="form-control dinheiro" aria-label="Custo do produto" value="{{ old('custo', '') }}">
      <input id="custo_produto_hidden" type="hidden" name="custo">
    </div>
</div>

<div class="form-group hidden">
    {!! Form::label('Frete Geral') !!}
    <div class="input-group">
      <span class="input-group-addon">%</span>
      <input id="frete_produto" type="text" class="form-control dinheiro" aria-label="Frete do produto" value="0">
      <input id="frete_produto_hidden" type="hidden" name="frete" value="0">
    </div>
</div>

<div class="form-group">
    {!! Form::label('Despesas sobre o Produto') !!}
    <a class="pull-right" href="../impostos/create" target="_blank">Despesa ainda não cadastrada?</a>
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></span></span>
      <select class="form-control impostos" id="impostos" name="imposto_id"></select>
      <span id="addimposto" class="btn input-group-addon btn-primary addimposto"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></span>
    </div>
    <input id="impostos_id" name="impostos_id" type="text" class="impostos_id hidden">
</div>
<div class="form-group"><h3 class="selecionarimpostos" id="selecionarimpostos"></h3></div>

<div class="form-group">
    {!! Form::label('Agregado da Empresa') !!}
    <div class="input-group">
      <span class="input-group-addon">%</span>
      <input id="agregado_produto" type="text" class="form-control dinheiro" aria-label="agregado" value="{{ old('valor_agregado', '') }}">
      <input id="agregado_produto_hidden" type="hidden" name="valor_agregado">
    </div>
</div>

<div class="form-group">
    {!! Form::label('Preço de Venda') !!}
    <div class="input-group">
      <span class="input-group-addon">R$</span>
      <input id="preco_produto" type="text" class="form-control dinheiro" aria-label="Valor do produto" value="{{ old('preco', '') }}">
      <input id="preco_produto_hidden" type="hidden" name="preco">
    </div>
</div>


<div class="form-group">
    {!! Form::label('Descrição') !!}
    {!! Form::textarea('descricao', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe alguma descrição para o produto')) !!}
</div>

<div class="form-group">
    <button class="btn btn-primary salvar-produto" url="/produtos">Salvar</button>
    <a href="{{url('produtos')}}" class="btn btn-default">Cancelar</a>
</div>

{!! Form::close() !!}

@endsection
