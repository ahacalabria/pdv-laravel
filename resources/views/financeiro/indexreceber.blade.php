@extends('layouts.padrao')

@section('styles')
    <link href="{{asset('dist/dataTables/css/dataTables.bootstrap.min.css')}}" />
@stop

@section('scripts')
    <script src="{{ asset('dist/dataTables/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('dist/dataTables/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/autoNumeric.js') }}"></script>
    <script type="text/javascript" language="javascript" class="init">

$(document).ready(function() {
table = $('#financeiro').DataTable( {
  "order": [[0, "desc"]],
  "columnDefs": [
      { "orderable": false, "targets": [2] },
      /*{ "sWidth": "50px", "targets": [0] },*/
      { "sClass": "center", "targets": [1, 2] }
  ],
   "search": {
      "regex": true
    },
  "lengthMenu": [10, 25, 50],
    processing: true,
    serverSide: true,
    "ajax": {  
        "url": "{{ URL::to('parceladoreceber/datatables') }}",
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
        {"data":"financeiro_receber.venda_id"},
        { "data": "financeiro_receber.venda.cliente.nome" },
        { "data": "financeiro_receber.recebedor.nome" },
        {"data":"valor",
            "className":"left",
            "render":function(data, type, full, meta){
              return Number(data).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2});
            }
           },
         {"data":"financeiro_receber.quantidade_parcelas",
            "className":"left",
            "render":function(data, type, full, meta){
               return full.numero +'/'+ full.financeiro_receber.quantidade_parcelas;
            }
           },
           {"data": 'data_vencimento'},
           {"data": 'data_pago'},
        { "data": "id" }
    ],
    "columnDefs":[
        {
            "render":function(data, type, row){
                var dtStart = data;
                var dtStartWrapper = moment(dtStart);
                return dtStartWrapper.format('DD/MM/YYYY');
            },
            "targets":6
        },
        {
            "render":function(data, type, full, row){
                if(full.status!="pago") return "-";
                var dtStart = data;
                var dtStartWrapper = moment(dtStart);
                return dtStartWrapper.format('DD/MM/YYYY');
            },
            "targets":7
        },
        {
            "render":function(data, type, full, row){
                return ' <button class="btn btn-primary pull-left view-recebimento"  current_id="'+data+'" url="parceladoreceber/'+full.financeiro_receber_id+'" data-toggle="tooltip" data-placement="top" title="Ver detalhes"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></button>'
                +'<button class="btn btn-success pull-left'+ ((full.status != "pago") ? ' view-recebimento-cash" data-toggle="tooltip" data-placement="top" title="Receber"' : '"disabled') +' url="parceladoreceber/'+data+'" valor_parcela="'+full.valor+'" ><span class="glyphicon glyphicon-usd" aria-hidden="true" current_id="'+data+'"></span></button>';
            },
            "targets":8
        },
        {
            "visible":true, "targets": [3]
        }
    ],
} );
table.order([0, 'desc']).draw();

$('.input-sm').keyup( function () {
table
.search(
jQuery.fn.DataTable.ext.type.search.string( this.value )
)
.draw();
} );
// Setup - add a text input to each footer cell
$('table#financeiro tfoot th').each( function () {
var title = $(this).text();
$(this).html( '<input type="text" style="width: 100%;" placeholder="Buscar '+title+'" />' );
} );
$('table#financeiro thead th').each( function (i) {
var title = $(this).text();
if(i==0)
  $(this).html( '<label>'+title+'</label><input id="search_for_codigo" type="text" style="width: 100%;" placeholder="Buscar '+title+'" />' );
else
  $(this).html( '<label>'+title+'</label><input type="text" style="width: 100%;" placeholder="Buscar '+title+'" />' );
} );

// Apply the search
table.columns().every( function (i) {
var that = this;

$( 'input', this.footer() ).on( 'keyup change', function () {
   if ( that.search() !== this.value ) {
     if(i==0) {
       if(this.value == ""){
         that.search( this.value ).draw();
       }else{
         that.search("^"+this.value+"$", true, false).draw();
       }
     }
     else
      that.search( this.value ).draw();
   }
} );
$( 'input', this.header() ).on( 'keyup change', function () {
   if ( that.search() !== this.value ) {
     if(i==0) {
       if(this.value == ""){
         that.search( this.value ).draw();
       }else{
         that.search("^"+this.value+"$", true, false).draw();
       }
     }
     else
      that.search( this.value ).draw();
   }
} );
} );
});
</script>
@stop

@section('content')
<div class="page-header">
  <h1>Financeiro <small>Contas a Receber</small></h1>
</div>
@if(Session::has('message'))
 <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Sucesso!</strong> {{ Session::get('message') }}
      </div>
@endif
<div>
<div style="clear:both;"></div>
</div>
<hr style="clear:both;" />
<table id="financeiro" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Cod. Venda</th>
            <th>Cliente</th>
            <th>Recebedor</th>
            <th>Val. Total</th>
            <th>Parcelas</th>
            <th>Vencimento</th>
            <th>Dt. Pagamento</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Cod. Venda</th>
            <th>Cliente</th>
            <th>Recebedor</th>
            <th>Val. Total</th>
            <th>Parcelas</th>
            <th>Vencimento</th>
            <th>Dt. Pagamento</th>
            <th>Ações</th>
        </tr>
    </tfoot>
</table>
<hr style="clear:both;" />

<!-- Modal -->
<div class="modal fade" id="modalViewRecebimento" tabindex="-1" role="dialog" aria-labelledby="Detalhes de Recebimento">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detalhes de Recebimento</h4>
      </div>
      <div id="modal-conteudo" class="modal-body">
        <div class="alert " role="alert">Legenda:<br>
            <span class="label label-primary">Parcela Atual</span>
            <span class="label alert-success">Parcela Paga</span>
            <span class="label alert-warning">Parcela Pendente</span>
            <span class="label alert-danger">Parcela Atrasada</span>
        </div>
        <table class="table table-condensed">
          <thead>
            <tr>
                <th>#</th>
                <th>Valor Parcela</th>
                <th>Val. Pago</th>
                <th>Data Vencimento</th>
                <th>Data Pagamento</th>
                <th>Status</th>
                <th>Ação</th>
            </tr>
          </head>
          <tbody>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

{{ Form::open(array('id'=>'form', 'route' => array('financeiroreceber.update', 'XXX'), 'class' => 'form' , 'method' => 'PUT')) }}
<div class="modal fade" id="modalViewRecebimentoCash" tabindex="-1" role="dialog" aria-labelledby="Detalhes de Recebimento">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detalhes de Recebimento</h4>
      </div>
      <div id="modal-conteudo2" class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button id="edit-sending-cash" type="button" class="btn btn-success print-cupom"><span class="glyphicon glyphicon-usd"></span> SALVAR</button>
      </div>
    </div>
  </div>
</div>
      {!! Form::close() !!}
@stop
