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
          "order": [[0, "desc"]],
          "columnDefs": [
              { "orderable": false, "targets": [2] },
              /*{ "sWidth": "50px", "targets": [0] },*/
              { "sClass": "center", "targets": [1, 2] }
          ],
           "search": {
              "regex": true
            },
          lengthMenu: [10, 25, 50],
          processing: true,
          serverSide: true,
            "ajax": {
                "url": "/vendas/datatables",
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
                { "data": "cliente.nome" },
                { "data": "vendedor.nome" },
                { "data": "data_venda" },
                { "data": "status" },
                { "data": "id" }
            ],
            "columnDefs":[
              {
                "render":function(data, type, full, row){
                  // console.log({data})
                    if(data==undefined || data==null || data.trim()=="") return full.cliente ? full.cliente.razao_social : 'NÃO INFORMADO';
                    return data;
                },
                "targets":1
              },
              {
                "render":function(data, type, row){
                    var dtStart = data;
                    var dtStartWrapper = moment(dtStart);
                    return dtStartWrapper.format('DD/MM/YYYY HH:mm');
                },
                "targets":3
              },
              {
                "render":function(data, type, full ,row){
                  var url_temp = "{{ url( 'vendas/')}}/";
                    return '<a class="btn btn-warning pull-left '+ ((full.status === "aberta") ? ' link-edit-venda" href="../vendas/'+data+'/edit/" data-toggle="tooltip" data-placement="top" title="Editar venda"' : '" disabled ') +'><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> '
                    @if(Auth::user()->level != "vendedor") +'<button class="btn btn-danger pull-left '+ ((full.status != "cancelada") ? 'cancelar-venda" url="vendas/'+data+'" data-toggle="tooltip" data-placement="top" title="Cancelar venda"' : '" disabled ') +'><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> '@endif
                    +' <button class="btn btn-primary pull-left view-venda" url="'+url_temp+data + '" data-toggle="tooltip" data-placement="top" title="Ver detalhes"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></button>'
                    @if(Auth::user()->level != "vendedor")+' <button type="button" class="btn btn-success pull-left '+ ((full.status == "aberta") ? 'pagarvendabt " url="pagamentovenda/'+data+'" data-toggle="tooltip" data-placement="top" title="Pagar venda"' : '" disabled') +' ><span class="glyphicon glyphicon-usd" aria-hidden="true"></span></button>'@endif
                    @if(Auth::user()->level != "vendedor")+' <button class="btn btn-info pull-left '+((full.status != "cancelada") ? 'print-cupom" url="printcupom/'+data+'" data-toggle="tooltip" data-placement="top" title="Imprimir cupom"' : '" disabled') +' ><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>'@endif
                    // +' <button class="btn btn-default pull-left '+((full.status == "fechada") ? 'print-cupom2" url="printcupom2/'+data+'" data-toggle="tooltip" data-placement="top" title="Imprimir cupom A4"' : '" disabled') +' ><span class="glyphicon glyphicon-open-file" aria-hidden="true"></span></button>';
                    @if(Auth::user()->level != "vendedor")+' <a class="btn btn-default pull-left '+((full.status != "cancelada") ? '" href="/dopdf/cupom-a4.php?id_venda='+data+'" target="_blank" data-toggle="tooltip" data-placement="top" title="Imprimir cupom A4"' : '" disabled') +' ><span class="glyphicon glyphicon-open-file" aria-hidden="true"></span></button>';@endif
                },
                "targets":5
              }
            ],
        } );
        table.order([0, 'desc']).draw();

  //   $('.input-sm').keyup( function () {
  //     table
  //     .search(
  //       jQuery.fn.DataTable.ext.type.search.string( this.value )
  //     )
  //     .draw();
  //   } );
  //   // Setup - add a text input to each footer cell
  //   $('table#vendas tfoot th').each( function () {
  //       var title = $(this).text();
  //       $(this).html( '<input type="text" style="width: 100%;" placeholder="Buscar '+title+'" />' );
  //   } );
  //   $('table#vendas thead th').each( function (i) {
  //       var title = $(this).text();
  //       if(i==0)
  //         $(this).html( '<label>'+title+'</label><input id="search_for_codigo" type="text" style="width: 100%;" placeholder="Buscar '+title+'" />' );
  //       else
  //         $(this).html( '<label>'+title+'</label><input type="text" style="width: 100%;" placeholder="Buscar '+title+'" />' );
  //   } );

  //   // Apply the search
  //  table.columns().every( function (i) {
  //      var that = this;

  //      $( 'input', this.footer() ).on( 'keyup change', function () {
  //          if ( that.search() !== this.value ) {
  //            if(i==0) {
  //              if(this.value == ""){
  //                that.search( this.value ).draw();
  //              }else{
  //                that.search("^"+this.value+"$", true, false).draw();
  //              }
  //            }
  //            else
  //             that.search( this.value ).draw();
  //          }
  //      } );
  //      $( 'input', this.header() ).on( 'keyup change', function () {
  //          if ( that.search() !== this.value ) {
  //            if(i==0) {
  //              if(this.value == ""){
  //                that.search( this.value ).draw();
  //              }else{
  //                that.search("^"+this.value+"$", true, false).draw();
  //              }
  //            }
  //            else
  //             that.search( this.value ).draw();
  //          }
  //      } );
  //  } );
 });
  </script>
@stop

@section('content')
<div class="page-header">
  <h1>Vendas <small>Lista</small></h1>
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
  <div id="msgOK" class="alert alert-success" role="alert"></div>
  <div id="msgERRO" class="alert alert-danger" role="alert"></div>
<a href="{!!URL::route('vendas.create')!!}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nova Venda</a>
<div style="clear:both;"></div>
</div>
<hr style="clear:both;" />
<!-- <select class="form-control pull-right" name="">
  <option value="">Teste</option>
</select> -->
  <table id="vendas" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Vendedor</th>
            <th>Data da Venda</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
          <th>#</th>
          <th>Cliente</th>
          <th>Vendedor</th>
          <th>Data da Venda</th>
          <th>Status</th>
          <th>Ações</th>
        </tr>
    </tfoot>
</table>
<hr/>
  <a href="{!!URL::route('vendas.create')!!}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nova Venda</a>

  <!-- Modal -->
  <div class="modal fade" id="modalViewVenda" tabindex="-1" role="dialog" aria-labelledby="Detalhes da Venda">
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
          <!-- <button type="submit" class="btn btn-info print-cupom"><span class="glyphicon glyphicon-print"></span> IMPRIMIR CUPOM NÃO FISCAL</button> -->
        </div>
      </div>
    </div>
  </div>


@stop
