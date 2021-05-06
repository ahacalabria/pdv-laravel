@extends('layouts.padrao')
@section('content')
<div class="page-header">
  <h1>Editando <small>Despesa sobre o produto</small></h1>
</div>
{{ Form::model($imposto, array('route' => array('impostos.update', $imposto->id), 'class' => 'form' ,'id'=>'imposto_form', 'method' => 'PUT')) }}

<div class="form-group">
    {!! Form::label('Nome da Despesa') !!}
    <input class="form-control" type="text" name="nome" value="{{$imposto->nome}}" required placeholder='Informe um nome para a despesa'>
</div>

<div class="form-group">
    {!! Form::label('Valor da Despesa') !!}
    <div class="input-group">
      <span class="input-group-addon">%</span>
      <input id="valor_imposto" class="form-control dinheiro" value="{{$imposto->valor}}" required placeholder='Informe um valor para a despesa'>
      <input id="valor_imposto_hidden" class="form-control" type="hidden" name="valor">
    </div>
</div>

<div class="form-group">
    <input id="submit_edit_imposto" class="btn btn-primary" value="Editar" type="submit">
    <a href="/impostos" class="btn btn-default">Cancelar</a>
</div>

{!! Form::close() !!}

@endsection
