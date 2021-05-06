@extends('layouts.padrao')

@section('content')

<div class="page-header">
  <h1>Visualizando @if($pessoa->tipo_cadastro == 'cliente') Cliente  @elseif( $pessoa->tipo_cadastro == 'funcionario') Funcionario @else Fornecedor @endif <small> @if($pessoa->nome == "") {{$pessoa->razao_social}} @else {{$pessoa->nome}} @endif</small></h1>
</div>

@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">

{{ Form::model($pessoa, array('route' => array('pessoa.update', $pessoa->id), 'method' => 'PUT')) }}

<div class="form-group hidden">
  {!! Form::label('Tipo do cadastro') !!}
  {!! Form::select('tipo_cadastro', $tipo_cadastro,$pessoa->tipo_cadastro, ['class' => 'form-control hidden']) !!}
  {!! Form::select('tipo', $tipo,$pessoa->tipo, ['id'=>'tipo', 'class' => 'form-control']) !!}
</div>

<div class="form-group">
  {!! Form::label('Tipo') !!}
  <br><small>@if($pessoa->tipo == "f")Pessoa física @else Pessoa Jurídica @endif</small>
</div>

@if($pessoa->tipo == "f")
<div class="form-group">
{!! Form::label('Sexo') !!}
<br><small>@if($pessoa->sexo == "f")Feminino @else Masculino @endif</small>
</div>
@endif

@if($pessoa->tipo_cadastro == 'funcionario')
<div class="form-group" >
    {!! Form::label('Cargo') !!}
    <br><small>{{$pessoa->cargo}}</small>
</div>
@endif

<div class="form-group" id="nome">
    {!! Form::label('Nome') !!}
    <br><small>{{$pessoa->nome}}</small>
</div>

<div class="form-group" id="sobrenome">
    {!! Form::label('Sobrenome') !!}
    <br><small>{{$pessoa->sobrenome}}</small>
</div>

<div class="form-group" id="razao-social">
    {!! Form::label('Razão Social') !!}
    <br><small>{{$pessoa->razao_social}}</small>
</div>
<div class="form-group" id="nome-fantasia">
    {!! Form::label('Nome fantasia') !!}
    <br><small>{{$pessoa->nome_fantasia}}</small>
</div>

<div class="form-group" id="cpf">
    {!! Form::label('CPF') !!}
    <br><small>{{$pessoa->cpf}}</small>
</div>

<div class="form-group" id="cnpj">
    {!! Form::label('CNPJ') !!}
    <br><small>{{$pessoa->cnpj}}</small>
</div>

<div class="form-group" id="rg">
    {!! Form::label('RG') !!}
    <br><small>{{$pessoa->rg}}</small>
</div>
<div class="form-group" id="ie">
    {!! Form::label('Incrição Estadual') !!}
    <br><small>{{$pessoa->ie}}</small>
</div>

@if($pessoa->tipo == "f")
<div class="form-group">
              {!! Form::label('Data de nascimento') !!}
              <br><small>{{date('d/m/Y', strtotime($pessoa->data_nascimento))}}</small>
</div>
@endif

<div class="form-group">
  {!! Form::label('Estado') !!}
    <br><small>{{$pessoa->estado->nome}}</small>
</div>

<div class="form-group">
  {!! Form::label('Cidade') !!}
<br><small>{{$pessoa->cidade->nome}}</small>
</div>

<div class="form-group">
    {!! Form::label('Endereço') !!}
    <br><small>{{$pessoa->endereco}}</small>
</div>

<div class="form-group">
    {!! Form::label('Bairro') !!}
    <br><small>{{$pessoa->bairro}}</small>
</div>

<div class="form-group">
    {!! Form::label('CEP') !!}
    <br><small>{{$pessoa->cep}}</small>
</div>

<div class="form-group">
    {!! Form::label('Telefone 1') !!}
    <br><small>{{$pessoa->telefone_1}}</small>
</div>

<div class="form-group">
    {!! Form::label('Telefone 2') !!}
    <br><small>{{$pessoa->telefone_2}}</small>
</div>

<div class="form-group">
    {!! Form::label('Endereço de email') !!}
    <br><small>{{$pessoa->email}}</small>
</div>

<div class="form-group">
    {!! Form::label('Site') !!}
    <br><small>{{$pessoa->site}}</small>
</div>

<div class="form-group" id="nome-responsavel">
    {!! Form::label('Nome responsável') !!}
    <br><small>{{$pessoa->nome_responsavel}}</small>
</div>

<div class="form-group" id="telefone-responsavel">
    {!! Form::label('Telefone Responsável') !!}
    <br><small>{{$pessoa->telefone_responsavel}}</small>
</div>

<div class="form-group hidden">
  {!! Form::label('Banco') !!}
  <br><small>{{$pessoa->banco->title}}</small>
</div>

<div class="form-group hidden">
    {!! Form::label('Numero da Agência') !!}
    <br><small>{{$pessoa->agencia}}</small>
</div>

<div class="form-group hidden">
    {!! Form::label('Numero da Conta') !!}
    <br><small>{{$pessoa->conta}}</small>
</div>

<div class="form-group">
    {!! Form::label('Observação') !!}
    <br><small>{{$pessoa->observacao}}</small>
</div>

<div class="form-group">
    <a href="{{ url()->previous() }}" class="btn btn-default">Voltar</a>
</div>
{!! Form::close() !!}

@endsection
