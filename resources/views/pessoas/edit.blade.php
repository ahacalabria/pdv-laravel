@extends('layouts.padrao')

@section('content')

<div class="page-header">
  <h1>Editando @if($pessoa->tipo_cadastro == 'cliente') Cliente  @elseif( $pessoa->tipo_cadastro == 'funcionario') Funcionario @else Fornecedor @endif <small> @if($pessoa->nome == "") {{$pessoa->razao_social}} @else {{$pessoa->nome}} @endif</small></h1>
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
  {!! Form::select('tipo_cadastro', $tipo_cadastro,$pessoa->tipo_cadastro, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
  {!! Form::label('Tipo') !!}
  {!! Form::select('tipo', $tipo,$pessoa->tipo, ['id'=>'tipo', 'class' => 'form-control']) !!}
</div>

<div class="form-group" id="sexo">
{!! Form::label('Sexo') !!}
{!! Form::select('sexo', ['f' => "Feminino", 'm' => "Masculino"],$pessoa->sexo, ['class' => 'form-control']) !!}
</div>

@if($pessoa->tipo_cadastro == 'funcionario')
<div class="form-group">
    {!! Form::label('Cargo') !!}
    {!! Form::text('cargo', null,
        array(
              'class'=>'form-control',
              'placeholder'=>'Informe um cargo')) !!}
</div>
@endif

<div class="form-group" id="nome">
    {!! Form::label('Nome') !!}
    {!! Form::text('nome', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe um Nome')) !!}
</div>

<div class="form-group hidden" id="sobrenome">
    {!! Form::label('Sobrenome') !!}
    {!! Form::text('sobrenome', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe um Sobrenome')) !!}
</div>

<div class="form-group" id="razao-social">
    {!! Form::label('Razão Social') !!}
    {!! Form::text('razao_social', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe a Razão Social')) !!}
</div>
<div class="form-group hidden" id="nome-fantasia">
    {!! Form::label('Nome fantasia') !!}
    {!! Form::text('nome_fantasia', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe um nome fantasia caso possua')) !!}
</div>

<div class="form-group" id="cpf">
    {!! Form::label('CPF') !!}
    {!! Form::text('cpf', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe o CPF')) !!}
</div>

<div class="form-group" id="cnpj">
    {!! Form::label('CNPJ') !!}
    {!! Form::text('cnpj', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe o CNPJ')) !!}
</div>

<div class="form-group" id="rg">
    {!! Form::label('RG') !!}
    {!! Form::text('rg', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe o RG')) !!}
</div>
<div class="form-group" id="ie">
    {!! Form::label('Incrição Estadual') !!}
    {!! Form::text('ie', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe o IE')) !!}
</div>

<div class="form-group" id="data-nascimento">
              {!! Form::label('Data de nascimento') !!}
              <div class="form-group">
                <div id="datavencimentoin" class="input-group datepickervecimento">
                  <input name="data_nascimento" class="form-control" id="datavencimentoinput" type="text" value="{{ date('d/m/Y H:i', strtotime($pessoa->data_nascimento)) }}">
                  <span class="input-group-addon btn">
                    <span class="glyphicon glyphicon-calendar"></span></span>
                  </div>
                </div>
</div>

<div class="form-group">
  {!! Form::label('Estado') !!}
    <select id="estados" class="form-control" name="estado_id">
      @foreach($estados as $estado)
        <option value={{ $estado->id }} @if($pessoa->estado_id == $estado->id ) selected="selected" @endif >{{ $estado->nome }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
  {!! Form::label('Cidade') !!}

    <select id="cidades" class="form-control" name="cidade_id">
      <option>SELECIONE UMA CIDADE</option>
    </select>
   <input type="hidden" id="cidade_pessoa" value="{{ $pessoa->cidade_id }}">
</div>

<div class="form-group">
    {!! Form::label('Endereço') !!}
    {!! Form::text('endereco', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe um endereço')) !!}
</div>

<div class="form-group">
    {!! Form::label('Bairro') !!}
    {!! Form::text('bairro', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe um bairro')) !!}
</div>

<div class="form-group">
    {!! Form::label('CEP') !!}
    {!! Form::text('cep', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe um cep válido')) !!}
</div>

<div class="form-group">
    {!! Form::label('Telefone 1') !!}
    {!! Form::text('telefone_1', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe um número para contato')) !!}
</div>

<div class="form-group">
    {!! Form::label('Telefone 2') !!}
    {!! Form::text('telefone_2', null,
        array('class'=>'form-control','placeholder'=>'Informe outro número para contato')) !!}
</div>

<div class="form-group">
    {!! Form::label('Endereço de email') !!}
    {!! Form::text('email', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe um endereço de email válido')) !!}

              @if ($errors->has('email'))
                  <span class="help-block">
                      <strong>{{ $errors->first('email') }}</strong>
                  </span>
              @endif
</div>

<div class="form-group">
    {!! Form::label('Site') !!}
    {!! Form::text('site', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe um endereço para um site caso possua')) !!}
</div>

<div class="form-group" id="nome-responsavel">
    {!! Form::label('Nome responsável') !!}
    {!! Form::text('nome_responsavel', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe o nome de algum responsável caso necessário')) !!}
</div>

<div class="form-group" id="telefone-responsavel">
    {!! Form::label('Telefone Responsável') !!}
    {!! Form::text('telefone_responsavel', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe um número para contato do responsável')) !!}
</div>

<div class="form-group hidden">
  {!! Form::label('Banco') !!}

    <select id="banco_id" class="form-control js-example-basic-single js-example-responsive" name="banco_id">
      @foreach($bancos as $banco)
        <option value={{ $banco->id }}>{{ $banco->title }}</option>
        @endforeach
    </select>
</div>

<div class="form-group hidden">
    {!! Form::label('Numero da Agência') !!}
    {!! Form::text('agencia', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe o número da agencia')) !!}
</div>

<div class="form-group hidden">
    {!! Form::label('Numero da Conta') !!}
    {!! Form::text('conta', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe o número da conta')) !!}
</div>

<div class="form-group">
    {!! Form::label('Observação') !!}
    {!! Form::textarea('observacao', null,
        array('class'=>'form-control',
              'placeholder'=>'Informe alguma informação extra caso necessário')) !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" value="Salvar" type="submit">
    <a href="{{ url()->previous() }}" class="btn btn-default">Cancelar</a>
</div>
{!! Form::close() !!}

@endsection
