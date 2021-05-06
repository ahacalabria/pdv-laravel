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
        table = $('#vendas').DataTable( {
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "/tipopagamentos/datatables",
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
                { "data": "tipo" },
                { "data": "created_at" },
                { "data": "id" }
            ],
            "columnDefs":[
              {
                  "render":function(data, type, row){
                      //return data + 'oi';
                      var dtStart = data;
                      var dtStartWrapper = moment(dtStart);
                      return dtStartWrapper.format('DD/MM/YYYY HH:mm');
                  },
                  "targets":2
              },
                {
                    "render":function(data, type, row){
                        return '<a class="btn btn-warning pull-left" href="../tipopagamentos/'+data+'/edit/"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> ';
                        // '<button class="btn btn-danger pull-left deletar" url="tipopagamentos/'+data+'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button> '
                        // +' <a class="btn btn-primary pull-left"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>';
                    },
                    "targets":3
                }
            ],


        } );
    } );
    </script>
@stop

@section('content')
<div class="page-header">
  <h1>Tipo de Pagamentos <small>Lista</small></h1>
</div>
  <!-- will be used to show any messages -->
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif


<div>
  <div id="msgOK" class="alert alert-success" role="alert"></div>
  <div id="msgERRO" class="alert alert-danger" role="alert"></div>
<a href="{!!URL::route('tipopagamentos.create')!!}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Novo Tipo Pagamento</a>
<div style="clear:both;"></div>
</div>
<hr style="clear:both;" />
  <table id="vendas" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Tipo</th>
            <th>Data de Criação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
          <th>#</th>
          <th>Tipo</th>
          <th>Data de Criação</th>
          <th>Ações</th>
        </tr>
    </tfoot>
</table>
<hr/>
  <a href="{!!URL::route('tipopagamentos.create')!!}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Novo Tipo Pagamento</a>
@stop
