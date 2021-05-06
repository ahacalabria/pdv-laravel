@extends('layouts.padrao')

@section('content')
<div class="page-header">
  <h1>Relatórios <small>Tipos</small></h1>
</div>
@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif
<div class="row">
    <div class="col-md-4">
      <h3>Recursos Humanos</h3>
      <ul>
        <li><a href="{{url('relatorio/fornecedores')}}">Relatório de Fornecedores</a></li>
        <li><a href="{{url('relatorio/clientes')}}">Relatório de Clientes</a></li>
        <li><a href="{{url('relatorio/funcionarios')}}">Relatório de Funcionários</a></li>
      </ul>
    </div>
    <div class="col-md-4">
       <h3>Financeiro</h3>
      <ul>
        <li><a href="{{url('relatorio/vendas')}}">Relatório Geral de Vendas</a></li>
        <li><a href="{{url('relatorio/vendasresumido')}}">Relatório Resumido de Vendas</a></li>
        <li><a href="{{url('relatorio/balanco')}}">Relatório de Balanço</a></li>
      </ul>
    </div>
    <div class="col-md-4">
       <h3>Produtos</h3>
      <ul>
        <li><a href="{{url('relatorio/prodinter')}}">Relatório de Produtos Internos</a></li>
        <li><a href="{{url('relatorio/prodexter')}}">Relatório de Produtos Externos</a></li>
        <li><a href="{{url('relatorio/movimentacao')}}">Relatório de Movimentação de Produtos</a></li>
        <li><a href="{{url('relatorio/despesas')}}">Relatório Geral de Despesas sobre Produto</a></li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
       <h3>Sistema</h3>
      <ul>
        <li><a href="{{url('log')}}">Auditoria</a></li>
      </ul>
    </div>
</div>


@endsection
