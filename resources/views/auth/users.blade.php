@extends('layouts.padrao')

@section('styles')
    <link href="{{asset('dist/dataTables/css/dataTables.bootstrap.min.css')}}" />
@stop

@section('scripts')
<script type="text/javascript">
  var table;
</script>
    <script src="{{ asset('dist/dataTables/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('dist/dataTables/js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {
        table = $('#users').DataTable( {
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "/users/datatables",
                // "dataSrc": "",
                "error": handleAjaxError
            },
             "oLanguage": {
                "sProcessing": "Aguarde enquanto os dados são carregados ...",
                "sLengthMenu": "Mostrar _MENU_ registros por pagina",
                "sZeroRecords": "Nenhum registro correspondente ao criterio encontrado",
                "sInfoEmtpy": "Exibindo 0 a 0 de 0 registros",
                "sInfo": "Exibindo de _START_ a _END_ de _TOTAL_ registros",
                "sInfoFiltered": "",
                "sSearch": "Procurar",
                "oPaginate": {
                   "sFirst":    "Primeiro",
                   "sPrevious": "Anterior",
                   "sNext":     "Próximo",
                   "sLast":     "Último"
                }
             },
            "columns": [
                { "data": "id" },
                { "data": "funcionario.nome" },
                { "data": "level" },
                { "data": "created_at" },
                { "data": "id" }
            ],
            "columnDefs":[
              {
                  "render":function(data, type, row){
                      var dtStart = data;
                      var dtStartWrapper = moment(dtStart);
                      return dtStartWrapper.format('DD/MM/YYYY HH:mm');
                  },
                  "targets":3
              },
                {
                    "render":function(data, type, row){
                        return '<a class="btn btn-warning pull-left" href="../users/'+data+'/edit/" data-toggle="tooltip" data-placement="top" title="Editar usuário"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> '+
                        '<button class="btn btn-danger pull-left deletar" url="users/'+data+'" data-toggle="tooltip" data-placement="top" title="Desativar usuário"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> '
                        +' <a class="btn btn-primary pull-left" href="../users/'+data+'/view/" data-toggle="tooltip" data-placement="top" title="Ver detalhes"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>';
                        // +' <a class="btn btn-success pull-left" data-toggle="tooltip" data-placement="top" title="Pagar venda"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span></a>'
                        // +' <button class="btn btn-info pull-left print-cupom" url="vendas/'+data+'" data-toggle="tooltip" data-placement="top" title="Imprimir cupom"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>';
                    },
                    "targets":4
                }
            ],


        } );
    } );
    </script>
@stop

@section('content')
<div class="page-header">
  <h1>Usuários <small>Lista</small></h1>
</div>
  <!-- will be used to show any messages -->
@if (Session::has('message'))
    <!-- <div class="alert alert-info">{{ Session::get('message') }}</div> -->
       <div class="alert alert-info alert-dismissible" role="alert">
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <strong>Informação do Sistema: </strong>{{ Session::get('message') }}
       </div>
@endif


<div>
  <!-- <div id="msgOK" class="alert alert-success" role="alert"></div> -->
  <!-- <div id="msgERRO" class="alert alert-danger" role="alert"></div> -->
<a href="{{ url('/register') }}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Novo Usuário</a>
<div style="clear:both;"></div>
</div>
<hr style="clear:both;" />
  <table id="users" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Funcionário</th>
            <th>Nível</th>
            <th>Data da criação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
          <th>#</th>
          <th>Funcionário</th>
          <th>Nível</th>
          <th>Data da criação</th>
          <th>Ações</th>
        </tr>
    </tfoot>
</table>
<hr/>
<a href="{{ url('/register') }}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Novo Usuário</a>

  <!-- Modal -->
  <!-- <div class="modal fade" id="modalViewVenda" tabindex="-1" role="dialog" aria-labelledby="Detalhes da Venda">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Detalhes da Venda</h4>
        </div>
        <div id="modal-conteudo" class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
          <button type="submit" class="btn btn-info print-cupom"><span class="glyphicon glyphicon-print"></span> IMPRIMIR CUPOM NÃO FISCAL</button>
        </div>
      </div>
    </div>
  </div> -->


@stop
