@extends('layouts.padrao')

@section('content')


{{ Form::model($tipoproduto, array('route' => array('tipoprodutos.update', $tipoproduto->id), 'class' => 'form' , 'method' => 'PUT')) }}

<div class="form-group">
    {!! Form::label('Nome de Tipo de Produto') !!}
    <input class="form-control" type="text" name="nome" value="{{$tipoproduto->nome}}" required placeholder='Informe um nome para o tipo produto'>
</div>


<div class="form-group">
    {{ Form::submit('Editar', array('class' => 'btn btn-primary')) }}
    <a href="/tipoprodutos" class="btn btn-primary">Cancelar</a>
</div>

{!! Form::close() !!}

@endsection
