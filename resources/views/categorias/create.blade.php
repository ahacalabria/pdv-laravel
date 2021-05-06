@extends('layouts.padrao')

@section('content')
<div class="page-header">
  <h1>Cadastro <small>Categoria</small></h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif

{!! Form::open(array('route' => 'categorias.store', 'class' => 'form')) !!}
<div class="form-group" id="nome">
    {!! Form::label('Nome') !!}
    {!! Form::text('nome', null,
        array(
              'class'=>'form-control',
              'placeholder'=>'Informe um Nome')) !!}
</div>
<div class="form-group">
    <input class="btn btn-primary" value="Salvar" type="submit">
    <a href="/categorias" class="btn btn-default">Cancelar</a>
</div>
{!! Form::close() !!}

@endsection
