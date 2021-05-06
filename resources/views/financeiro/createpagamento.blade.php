@extends('layouts.padrao')

@section('content')
<div class="page-header">
  <h1>Caixa <small>contas a pagar</small></h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif

{!! Form::open(array('route' => 'financeiropagar.store', 'class' => 'form' ,'id' => 'formulario')) !!}

<div class="col-sm-12">
<div class="form-group col-sm-3">
    {!! Form::label('Título da conta') !!}
      <input class="form-control" type="text" name="titulo"/>
</div>
<div class="form-group col-sm-9">
    {!! Form::label('Descrição da conta') !!}
    <input id="" class="form-control" type="text" name="descricao"/>
</div>
<div class="form-group col-sm-3">
  <div class="checkbox">
  <label>
    <input id="recorrencia" type="checkbox"> Recorrência
  </label>
</div>
</div>
<div class="form-group col-sm-3">
    {!! Form::label('Valor da conta') !!}
    <div class="input-group col-sm-12">
      <span class="input-group-addon">RS</span>
      <input id="valor_total_liquido" class="form-control dinheiro" type="text"/>
    </div>
</div>
<div class="form-group col-sm-3" id="datavencimento">
    <label>Vencimento </label>
    <div class="form-group">
      <div id="datavencimentoin" class="input-group datepickervecimento">
        <input class="form-control" id="datavencimentoinput" type="text">
        <span class="input-group-addon btn">
          <span class="glyphicon glyphicon-calendar"></span></span>
        </div>
      </div>
</div>

<div id="content" class="">
<!-- info  dynamically -->
</div>

<!-- </div> -->

<div class="form-group col-md-12">
<br>
    <input id="valor_total_hidden" type="hidden" name="valor_total"/>
    <input id="todasasparcelas" type="hidden" name="todasasparcelas">
    <input id="quantidade_parcelas" type="hidden" name="quantidade_parcelas">
    <input type="hidden" name="quantidade_parcelas_pagas" value="0">
    <input type="hidden" name="pessoa_recebeu_id" value="{{Auth::user()->pessoa_id}}">
    <button id="pagarcontaconfirm" class="btn btn-primary" type="button"><span class="glyphicon glyphicon-ok"></span> SALVAR CONTA</button>
    <a href="/financeiro/contasapagar" class="btn btn-default pull-right"><span class="glyphicon glyphicon-remove"></span> CANCELAR</a>
</div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalReceberVenda" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div id="modal-conteudo" class="row">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button id="salvar-cheque" url="/vendas" type="button" class="btn btn-primary salvar-cheque">Concluir</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalPagarContaConfirmar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal-title2"></h4>
      </div>
      <div class="modal-body">
        <div id="modal-conteudo2" class="row">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button id="salvarconta" type="button" class="btn btn-primary">Concluir</button>
      </div>
    </div>
  </div>
</div>

{!! Form::close() !!}

@endsection
