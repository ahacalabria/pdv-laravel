@extends('layouts.padrao')
@section('content')
<div class="page-header">
  <h1>Editando <small>Tipo pagamento</small></h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif
{{ Form::model($tipopagamento, array('route' => array('tipopagamentos.update', $tipopagamento->id), 'class' => 'form' , 'method' => 'PUT')) }}

<div class="form-group col-sm-12">
    {!! Form::label('Tipo') !!}
    <input class="form-control" type="text" name="tipo" value="{{$tipopagamento->tipo}}"/>
</div>

<div class="form-group">
    <button id="" class="btn btn-primary" type="submit" data-toggle="modal" data-target="#modalConfirmarVenda"> SALVAR</button>
    <a href="/tipopagamentos" class="btn btn-default"> CANCELAR</a>
</div>

{!! Form::close() !!}

@endsection
