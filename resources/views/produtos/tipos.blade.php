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
    <script src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js') }}"></script>
     <script src="{{ asset('//cdn.datatables.net/plug-ins/1.10.11/sorting/datetime-moment.js') }}"></script>
    <script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {
        table = $('#tipoprodutos').DataTable( {
            "ajax": {
                "url": "/tipoprodutos/all",
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
                { "data": "nome" },
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
                        return '<a class="btn btn-primary pull-left" href="../tipoprodutos/'+data+'/edit/">Editar</a>  '+
                        '<button class="btn btn-danger pull-left deletar" url="tipoprodutos/'+data+'">Deletar</button> '
                        +' <a class="btn btn-warning pull-left">Selecionar</a>';
                    },
                    "targets":3
                }
            ],


        } );
    } );
    </script>
@stop

@section('content')

  <!-- will be used to show any messages -->
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

<div>
  <div id="msgOK" class="alert alert-success" role="alert"></div>
  <div id="msgERRO" class="alert alert-success" role="alert"></div>
<a href="{!!URL::route('tipoprodutos.create')!!}" class="btn btn-success pull-right">Cadastrar Novo Tipo de Produtos</a>
<div style="clear:both;"></div>
</div>
<hr style="clear:both;" />
  <table id="tipoprodutos" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Criado em</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Criado em</th>
            <th>Ações</th>
        </tr>
    </tfoot>
</table>
<hr/>
  <a href="{!!URL::route('tipoprodutos.create')!!}" class="btn btn-success pull-right">Cadastrar Novo Tipo de Produtos</a>
@stop
