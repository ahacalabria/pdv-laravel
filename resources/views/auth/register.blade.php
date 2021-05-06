@extends('layouts.padrao')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Cadastrado de Acesso do Sistema</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/user') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">

                          <input id="user_name" type="hidden" name="name" value="">

                            <label for="name" class="col-md-4 control-label">Nome</label>
                            <div class="col-md-6">
                                <!-- <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"> -->
                                <select id="pessoa-nome" class="js-example-basic-single js-states form-control" name="pessoa_id">

                                </select>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                          <label for="name" class="col-md-4 control-label">Nível de Acesso</label>
                          <div class="col-md-6">
                          <select class="form-control" name="level">
                            <option value="vendedor">Vendedor</option>
                            <option value="caixa">Caixa</option>
                            <option value="gerente">Gerente</option>
                            <option value="administrador">Administrador</option>
                          </select>
                        </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" readonly value=@if (count($funcionarios) == 1) {{$funcionarios[0]->email}}@endif>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('limite_porcetagem') ? ' has-error' : '' }}">
                            <label for="limite_porcetagem" class="col-md-4 control-label">Limite Porcetagem</label>

                            <div class="col-md-6">
                                <!-- <input id="limite_porcetagem" type="number" class="form-control" name="limite_porcetagem" value="{{ old('limite_porcetagem') }}"> -->
                                <div class="input-group">
                                  <span class="input-group-addon"> %</span>
                                  <input name="limite_porcetagem" type="text" class="form-control" aria-label="Valor máximo para desconto em %">
                                </div>
                                @if ($errors->has('limite_porcentagem'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('limite_porcetagem') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('limite_dinheiro') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Limite Dinheiro</label>

                            <div class="col-md-6">
                                <!-- <input id="limite_dinheiro" type="limite_dinheiro" class="form-control" name="limite_dinheiro" value="{{ old('limite_dinheiro') }}"> -->

                                <div class="input-group">
                                  <span class="input-group-addon">R$</span>
                                  <input name="limite_dinheiro" type="text" class="form-control" aria-label="Valor máximo para desconto em %">
                                  <span class="input-group-addon">,00</span>
                                </div>

                                @if ($errors->has('limite_dinheiro'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('limite_dinheiro') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Senha</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirma Senha</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="glyphicon glyphicon-user"></i> Salvar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
