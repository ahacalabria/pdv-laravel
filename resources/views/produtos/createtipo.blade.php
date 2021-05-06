@extends('layouts.padrao')

@section('content')

{!! Form::open(array('route' => 'tipoprodutos.store', 'class' => 'form')) !!}

<div class="form-group">
    {!! Form::label('Nome de Tipo de Produto') !!}
    <input class="form-control" type="text" name="nome" required placeholder='Informe um nome para o tipo produto'>
    <input type="hidden" name="id">
</div>


<div class="form-group">
    <input class="btn btn-primary" value="Salvar" type="submit">
    <a href="/tipoprodutos" class="btn btn-primary">Cancelar</a>
</div>

{!! Form::close() !!}

@endsection
