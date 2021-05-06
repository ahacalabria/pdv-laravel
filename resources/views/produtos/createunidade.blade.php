@extends('layouts.padrao')
@section('content')
<div class="page-header">
  <h1>Cadastro <small>Unidade de Medida</small></h1>
</div>
{!! Form::open(array('route' => 'unidades.store', 'class' => 'form')) !!}

<div class="form-group">
    {!! Form::label('Nome da Unidade de Medida') !!}
    <input class="form-control" type="text" name="nome" required placeholder='Informe um nome para a unidade de medida'>
</div>

<div class="form-group">
    {!! Form::label('Sigla') !!}
    <input class="form-control" max="2" name="sigla" required placeholder='Informe uma sigla'>
</div>

<div class="form-group">
    <input class="btn btn-primary" value="Salvar" type="submit">
    <a href="/unidades" class="btn btn-default">Cancelar</a>
</div>

{!! Form::close() !!}

@endsection
