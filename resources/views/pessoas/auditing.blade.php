@extends('layouts.padrao')

@section('styles')
    <link href="{{asset('dist/dataTables/css/dataTables.bootstrap.min.css')}}" />
@stop

@section('scripts')
    <script src="{{ asset('dist/dataTables/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('dist/dataTables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js') }}"></script>
    <script src="{{ asset('//cdn.datatables.net/plug-ins/1.10.11/sorting/datetime-moment.js') }}"></script>
    <script src="{{ asset('//cdn.datatables.net/plug-ins/1.10.12/filtering/type-based/accent-neutralise.js') }}"></script>
    <script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {
        table = $('#pessoas').DataTable( {
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "{{ URL::to('log/datatables') }}",
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
                { "data": "user.id" },
                {"data":"user.name"},
                { "data": "owner_type" },
                { "data": "type" },
                {"data":"route"},
                { "data": "ip"  },
                { "data": "created_at" },
                { "data": "updated_at" },
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
                    "targets":6
                },
                {
                    "render":function(data, type, row){
                        //return data + 'oi';
                        var dtStart = data;
                        var dtStartWrapper = moment(dtStart);
                        return dtStartWrapper.format('DD/MM/YYYY HH:mm');
                    },
                    "targets":7
                },
                {
                    "render":function(data, type, row){
                        return ' <button class="btn btn-primary pull-left view-owner" data-id="'+data+'"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></button>';
                    },
                    "targets":8
                },
                {
                    "visible":true, "targets": [3]
                }
            ],

        } );
    } );
     $('.input-sm').keyup( function () {
        table
          .search(
            jQuery.fn.DataTable.ext.type.search.string( this.value )
          )
          .draw()
      } );

    $('body').on('click', '.view-owner',function(){
        var id = $(this).data('id');
        $.ajax({
                url: $urlserver+"/getlog/"+id,
                type: "get",
                success: function(data){
                    //data = JSON.parse(data);
                    var $str = "";

                        console.log(data.owner);

                        $.each(data.owner, function(key,valor){
                       $str +=key+': '+ valor+'<hr/>';
                    });



                     $('#conteudo').html($str);
                },
                error: function(data){
                  console.log(data);
                }
            });
        $('#ownerModal').modal('show');

    })
    </script>
@stop

@section('content')
<div class="page-header">
  <h1>Log/Auditoria <small>Lista</small></h1>
</div>
@if(Session::has('message'))
 <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Sucesso!</strong> {{ Session::get('message') }}
      </div>
@endif
<div>
  <div id="msgOK" class="alert alert-success" role="alert"></div>
  <div id="msgERRO" class="alert alert-danger" role="alert"></div>
<div style="clear:both;"></div>
</div>
  <table id="pessoas" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Usuário</th>
            <th>Tipo de Objeto</th>
            <th>Ação</th>
            <th>Origem</th>
            <th>IP</th>
            <th>Data da Ação</th>
            <th>Data Mudança</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
           <th>#</th>
            <th>Usuário</th>
            <th>Tipo de Objeto</th>
            <th>Ação</th>
            <th>Origem</th>
            <th>IP</th>
            <th>Data da Ação</th>
            <th>Data Mudança</th>
            <th>Ações</th>
        </tr>
    </tfoot>
</table>
<hr/>

<!-- Modal -->
<div class="modal fade" id="ownerModal" tabindex="-1" role="dialog" aria-labelledby="ownerModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ownerModalLabel">Informações Complementares</h4>
      </div>
      <div class="modal-body" id="conteudo">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sair</button>
      </div>
    </div>
  </div>
</div>
@stop
