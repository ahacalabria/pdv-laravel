@extends('layouts.padrao')
@section('content')
<div class="page-header">
  <h1>Visualizando <small>Produto</small></h1>
</div>
@if($errors->has())
@foreach ($errors->all() as $error)
<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Erro!</strong> {{ $error }}
</div>
@endforeach
@endif

<div class="form-group">
  {!! Form::label('Fornecedor') !!}
  <br><small>{{$produto->pessoa->razao_social}}</small>
</div>

<div class="form-group">
  {!! Form::label('Codigo do Produto') !!}
  <br><small>{{$produto->id}}</small>
</div>

<div class="form-group">
  {!! Form::label('Nome do Produto') !!}
  <br><small>{{$produto->titulo}}</small>

</div>

<div class="form-group">
  {!! Form::label('Categorias') !!}
  @foreach($produto->categorias as $categorias )
    <br><small>{{$categorias->nome}}</small>
  @endforeach
</div>

<div class="form-group">
  {!! Form::label('Subcategorias') !!}
  @foreach($produto->subcategorias as $subcategorias )
    <br><small>{{$subcategorias->nome}}</small>
  @endforeach
</div>

<div class="form-group">
  {!! Form::label('Unidade de Medida do Produto') !!}
  <br><small>{{$produto->unidade->nome}}</small>
</div>

<div class="form-group">
  {!! Form::label('Quantidade em Estoque') !!}
  <br><small>{{$produto->quantidade_estoque}}</small>
</div>

<div class="form-group">
  {!! Form::label('Preço de compra') !!}
  <br><small>{{$produto->custo}}</small>
</div>


<div class="form-group hidden">
  {!! Form::label('Frete do Produto') !!}
  <br><small>{{$produto->frete}}</small>
</div>

<div class="form-group">
  {!! Form::label('Despesas sobre o Produto') !!}
  @foreach($produto->impostos as $imposto )
    <br><small>{{$imposto->nome}} - {{$imposto->valor}}%</small>
  @endforeach
</div>

<div class="form-group">
  {!! Form::label('Agregado da Empresa') !!}
  <br><small>{{$produto->valor_agregado}} %</small>
</div>


<div class="form-group">
  {!! Form::label('Preço de Venda') !!}
  <br><small>{{$produto->preco}}</small>
</div>

<div class="form-group">
  {!! Form::label('Descrição') !!}
  <br><small>{{$produto->descricao}}</small>
</div>

<div class="form-group">
  <a href="/produtos" class="btn btn-default">Volta</a>
</div>

@endsection
