@extends('layouts.padrao')
@section('content')
<div class="page-header">
  <h1>Editando <small>Unidade de medida</small></h1>
</div>
{{ Form::model($unidade, array('route' => array('unidades.update', $unidade->id), 'class' => 'form' , 'method' => 'PUT')) }}

<div class="form-group">
    {!! Form::label('Nome do Unidade de Medida') !!}
    <input class="form-control" type="text" name="nome" value="{{$unidade->nome}}" required placeholder='Informe um nome para a unidade de medida'>
</div>

<div class="form-group">
    {!! Form::label('Sigla') !!}
    <input class="form-control" type="text" name="sigla" value="{{$unidade->sigla}}" required placeholder='Informe um nome para o imposto'>
</div>

<div class="form-group">
    {{ Form::submit('Editar', array('class' => 'btn btn-primary')) }}
    <a href="/unidades" class="btn btn-default">Cancelar</a>
</div>

{!! Form::close() !!}

@endsection
