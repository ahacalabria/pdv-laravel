@extends('layouts.padrao')
@section('content')
<div class="page-header">
  <h1>Cadastro <small>Tipo pagamento</small></h1>
</div>
{!! Form::open(array('route' => 'tipopagamentos.store', 'class' => 'form')) !!}

<div class="form-group col-sm-12">
    {!! Form::label('Tipo') !!}
    <input class="form-control" type="text" name="tipo" placeholder=''/>
</div>

<div class="form-group">
    <button id="" class="btn btn-primary" type="submit" data-toggle="modal" data-target="#modalConfirmarVenda"> SALVAR</button>
    <a href="/tipopagamentos" class="btn btn-default"> CANCELAR</a>
</div>

{!! Form::close() !!}

@endsection
