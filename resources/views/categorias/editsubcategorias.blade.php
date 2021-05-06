@extends('layouts.padrao')

@section('content')
<div class="page-header">
  <h1>Editando <small>Subcategoria</small></h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif

{{ Form::model($subcategoria, array('route' => array('subcategorias.update', $subcategoria->id), 'method' => 'PUT')) }}
{!! Form::label('Categoria') !!}
<select class="form-control" name="categoria_id">
  @foreach($categorias as $categoria)
  <option value={{ $categoria->id }} @if($categoria->id == $subcategoria->categoria_id ) selected="selected" @endif >{{ $categoria->nome }}</option>
  @endforeach
</select>

</br>


<div class="form-group" id="nome">
    {!! Form::label('Nome') !!}
    {!! Form::text('nome', null,
        array(
              'class'=>'form-control',
              'placeholder'=>'Informe um Nome')) !!}
</div>
<div class="form-group">
    <input class="btn btn-primary" value="Salvar" type="submit">
    <a href="/subcategorias/{{$subcategoria->id}}" class="btn btn-default">Cancelar</a>
</div>
{!! Form::close() !!}

@endsection
