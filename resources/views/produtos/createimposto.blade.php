@extends('layouts.padrao')
@section('content')
<div class="page-header">
  <h1>Cadastro <small>Despesa</small></h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif
{!! Form::open(array('route' => 'impostos.store', 'class' => 'form', 'id' => 'imposto_form')) !!}

<div class="form-group">
    {!! Form::label('Nome da Despesa') !!}
    <input class="form-control" type="text" name="nome"  placeholder='Informe um nome para despesa'>
</div>

<div class="form-group">
    {!! Form::label('Valor da Despesa') !!}
    <div class="input-group">
      <span class="input-group-addon">%</span>
      <input id="valor_imposto" class="form-control dinheiro"  placeholder='Informe um valor para despesa'>
      <input class="form-control" type="hidden" name="tipo" value="p">
      <input id="valor_imposto_hidden" class="form-control" type="hidden" name="valor">
    </div>
</div>

<div class="form-group">
    <input id="submit_new_imposto" class="btn btn-primary" value="Salvar" type="submit">
    <a href="{{ URL::previous() }}" class="btn btn-default">Cancelar</a>
</div>

{!! Form::close() !!}

@endsection
