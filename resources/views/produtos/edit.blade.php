@extends('layouts.padrao')
@section('content')
<div class="page-header">
  <h1>Editando <small>Produto</small>
  <button class="btn btn-primary pull-right updatecampos"type="button"><span class="glyphicon glyphicon-refresh"></span> Atualizar campos</button></h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif
{{ Form::model($produto, array('route' => array('produtos.update', $produto->id), 'class' => 'form' , 'method' => 'PUT')) }}

<div class="form-group">
    {!! Form::label('Fornecedor') !!}
    <a class="pull-right" href="../pessoa/create?tipo=fornecedor" target="_blank"> Fornecedor ainda não cadastrado?</a>
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
      <select class="form-control js-example-basic-single" id="fornecedores" name="pessoa_id" value="{{ old('pessoa_id', '') }}"></select>
      <!-- <select class="form-control" name="pessoa_id">
        @foreach($fornecedores as $fornecedor)
          <option value={{ $fornecedor->id }} @if($fornecedor->id == $produto->pessoa_id ) selected="selected" @endif> @if( $fornecedor->nome == '') {{$fornecedor->razao_social}} @else {{$fornecedor->nome}} @endif </option>
          @endforeach
      </select> -->
    </div>
</div>
@if(!empty($produto->pessoa_id))
<script>
  var pessoa_id = {{$produto->pessoa_id}};
  setTimeout(function(){
    $('#fornecedores').val(pessoa_id).trigger('change'); 
  },500);
</script>
@endif

<div class="form-group hidden">
    {!! Form::label('Codigo do Produto') !!}
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span></span>
      <input class="form-control" type="text" name="codigo" placeholder='Informe um codigo para o produto' value="{{$produto->codigo}}">
    </div>
</div>
<div class="form-group hidden">
    {!! Form::label('Codigo NCM do Produto') !!}
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span></span>
      <input class="form-control" type="text" name="codigo_ncm" placeholder='Informe um codigo ncm para o produto' value="{{$produto->codigo_ncm}}">
    </div>
</div>

<div class="form-group">
    {!! Form::label('Nome do Produto') !!}
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-font" aria-hidden="true"></span></span>
      <input id="editing_product" class="form-control" type="text" name="titulo" placeholder='Informe um título para o produto' value="{{$produto->titulo}}">
    </div>

</div>

<div class="form-group">
  {!! Form::label('Categorias') !!}
  <a class="pull-right" href="../categorias/create" target="_blank"> Categoria ainda não cadastrada?</a>
  <div class="panel-group" id="categorias" role="tablist" aria-multiselectable="true"></div>
  <input id="subcategorias_id" type="hidden" value="@foreach($produto->subcategorias as $subcategoria) @if($produto->subcategorias->first() == $subcategoria) {{''.$subcategoria->pivot->subcategoria_id}} @else {{','. $subcategoria->pivot->subcategoria_id}} @endif @endforeach">
  <input id="categorias_id" type="hidden" value="@foreach($produto->categorias as $categoria) @if($produto->categorias->first() == $categoria) {{''.$categoria->pivot->categoria_id}} @else {{','. $categoria->pivot->categoria_id}} @endif @endforeach">
</div>

<div class="form-group">
    {!! Form::label('Unidade de Medida do Produto') !!}
    <a class="pull-right" href="../unidades/create" target="_blank"> Unidade de medida ainda não cadastrada?</a>
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-glass" aria-hidden="true"></span></span>
      <select id="unidades" class="form-control" name="unidade_id">
        @foreach($unidades as $unidade)
          <option value={{ $unidade->id }} @if($unidade->id == $produto->unidade_id ) selected="selected" @endif >{{ $unidade->nome }}</option>
          @endforeach
      </select>
      <input type="hidden" id="unidade_salva" value={{$produto->unidade_id}}>
    </div>
</div>

<div class="form-group">
    {!! Form::label('Quantidade em Estoque') !!}
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-tag" aria-hidden="true"></span></span>
      <input id="quantidade_estoque" name="quantidade_estoque" type="text" class="form-control" aria-label="Quantidade em estoque" value="{{$produto->quantidade_estoque}}">
      <span id="spanUnidade" class="input-group-addon">{{$produto->unidade->nome}}</span>
    </div>
</div>

<div class="form-group">
    {!! Form::label('Preço de compra') !!}
    <div class="input-group">
      <span class="input-group-addon">R$</span>
      <input id="custo_produto" type="text" class="form-control dinheiro" aria-label="Custo do produto" value="{{$produto->custo}}">
      <input id="custo_produto_hidden" type="hidden" name="custo">
    </div>
</div>


<div class="form-group hidden">
    {!! Form::label('Frete do Produto') !!}
    <div class="input-group">
      <span class="input-group-addon">%</span>
      <input id="frete_produto" type="text" class="form-control dinheiro" aria-label="Frete do produto" value="{{$produto->frete}}">
      <input id="frete_produto_hidden" type="hidden" name="frete">
    </div>
</div>

<div class="form-group">
    {!! Form::label('Despesas sobre o Produto') !!}
    <a class="pull-right" href="../impostos/create" target="_blank">Despesa ainda não cadastrada?</a>
    <div class="input-group">
      <span class="input-group-addon"><span class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></span></span>
      <select class="form-control impostos" id="impostos" name="imposto_id"></select>
      <span id="addimposto" class="btn input-group-addon btn-primary addimposto">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
      </span>
    </div>
      <input id="impostos_id" name="impostos_id" type="text" class="impostos_id hidden" value="@foreach($produto->impostos as $imposto) @if($produto->impostos->first() == $imposto) {{''.$imposto->pivot->imposto_id}} @else {{','. $imposto->pivot->imposto_id}} @endif @endforeach">
</div>

<div class="form-group"><h3 class="selecionarimpostos" id="selecionarimpostos"></h3></div>

<div class="form-group">
    {!! Form::label('Agregado da Empresa') !!}
    <div class="input-group">
      <span class="input-group-addon">%</span>
      <input id="agregado_produto" type="text" class="form-control dinheiro" aria-label="agregado" value="{{$produto->valor_agregado}}">
      <input id="agregado_produto_hidden" type="hidden" name="valor_agregado">
    </div>
</div>

<div class="form-group">
    {!! Form::label('Preço de Venda') !!}
    <div class="input-group">
      <span class="input-group-addon">R$</span>
      <!-- <input name="preco" type="number" class="form-control" aria-label="Valor do produto" value="{{$produto->preco}}"> -->
      <input id="preco_produto" type="text" class="form-control dinheiro" aria-label="Valor do produto" value="{{$produto->preco}}">
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
    <button class="btn btn-primary editar-produto" url="/produtos">Salvar Alterações</button>
    <a href="/produtos" class="btn btn-default">Cancelar</a>
</div>

{!! Form::close() !!}

@endsection
