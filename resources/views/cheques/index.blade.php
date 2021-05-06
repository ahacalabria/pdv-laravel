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
        table = $('#chequestable').DataTable( {
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "/cheques/datatables",
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
                { "data": "historico" },
                { "data": "data_emissao" },
                { "data": "data_vencimento" },
                { "data": "valor" },
                { "data": "vendas" },
                { "data": "id" }
            ],
            "columnDefs":[
              {
                  "render":function(data, type, row){
                      return Number(data).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2});
                  },
                  "targets":5
              },
                {
                    "render":function(data, type, row){
                        var dtStart = data;
                        var dtStartWrapper = moment(dtStart);
                        return dtStartWrapper.format('DD/MM/YYYY');
                    },
                    "targets":[3,4]
                },
                {
                    "render":function(data, type, row){
                      var $id = data[0].id;
                        var url_temp = "{{ url( 'vendas/')}}"+"/"+$id;
                        console.log(url_temp);
                        return '<button class="btn btn-default pull-left view-venda" url="'+url_temp+ '" data-toggle="tooltip" data-placement="top" title="Ver detalhes"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></button>';
                    },
                    "targets":6
                },
                {
                    "render":function(data, type, row){
                        return '<button class="btn btn-success pull-left view-historico" url="cheques/'+data+'"><span class="glyphicon glyphicon-transfer" aria-hidden="true"></span></button>'+
                        // '<button class="btn btn-danger pull-left deletar" url="cheques/'+data+'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> '+
                        ' <button class="btn btn-primary pull-left view-cheque" url="cheques/'+data+'"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></button>';
                    },
                    "targets":7
                }
            ],


        } );
    } );
    </script>
@stop

@section('content')
<div class="page-header">
  <h1>Cheques <small>Lista</small></h1>
</div>
  <!-- will be used to show any messages -->
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

<div>
  <div id="msgOK" class="alert alert-success" role="alert"></div>
  <div id="msgERRO" class="alert alert-danger" role="alert"></div>
<div style="clear:both;"></div>
</div>
  <table id="chequestable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Eminente</th>
            <th>Histórico</th>
            <th>Data de Emissão</th>
            <th>Data de Vencimento</th>
            <th>Valor</th>
            <th>Venda</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
          <th>#</th>
          <th>Eminente</th>
          <th>Histórico</th>
          <th>Data de Emissão</th>
          <th>Data de Vencimento</th>
          <th>Valor</th>
          <th>Ações</th>
        </tr>
    </tfoot>
</table>
<hr/>

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


<!-- Modal -->
<div class="modal fade" id="modalReceberVenda" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal-title">Detalhes</h4>
      </div>
      <div class="modal-body">
        <div id="modal-conteudo-cheque" class="row">
          <div class="col-sm-12"><div class="form-group col-sm-4">
              <label>Pessoa</label>
              <select class="form-control" id="tipopessoa" readonly>
                <option value="f">Física</option>
                <option value="j">Jurídica</option>
              </select>
          </div>
          <div class="form-group col-sm-4">
              <label>Histórico do Cheque</label>
              <input class="form-control" type="text" readonly id="historico"/>
          </div>
          <div class="form-group col-sm-4">
              <label>Data de emissão</label>
              <div class="form-group">
                    <input id="dataemissao" readonly type="text" class="form-control"/>
               </div>
          </div></div>
          <div class="col-sm-12"><div class="form-group col-sm-6">
              <label>Nome do Emitente</label>
              <input class="form-control" readonly type="text" id="nomeeminente"/>
          </div>
          <div class="form-group col-sm-6">
              <label class="col-sm-12">Banco</label>
             <select disabled style="width: 100%" class="form-control js-example-basic-single banks" id="banco_id">
              </select>
          </div></div>
          <div class="col-sm-12"><div class="form-group col-sm-4">
              <label>Agência</label>
              <input class="form-control" type="text" readonly id="agencia"/>
          </div>
          <div class="form-group col-sm-4">
              <label>Conta corrente</label>
              <input class="form-control" type="text" readonly id="contacorrente"/>
          </div>
          <div class="form-group col-sm-4">
              <label>Número do Cheque</label>
              <input class="form-control" readonly type="text" id="numerocheque"/>
          </div></div>
          <div class="col-sm-12"><div class="form-group col-sm-4">
              <label>Valor</label>
                <input id="valor-cheque" readonly class="form-control dinheiro" type="text"/>
          </div>
          <div class="form-group col-sm-4">
              <label>Vencimento</label>
              <div class="form-group">
                    <input readonly type="text" class="form-control" id="datavencimento"/>
               </div>
          </div>
          <div class="form-group col-sm-4">
              <label>CPF/CPNJ do Cheque</label>
              <input class="form-control" readonly type="text" id="cpfcnpj"/>
          </div></div>
          <div class="col-sm-12"><div class="form-group col-sm-3">
              <label>Digitalização</label>
          </div>
          <div class="text-center col-sm-9"><img id="chequepreview" accept="image/*" style="width:30%;" alt="" class="img-responsive img-thumbnail"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalHistoricoCheque" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal-title">Acompanhamento</h4>
      </div>
      <div class="modal-body">
        <div id="modal-conteudo" class="row">
          {{ Form::open(array('id'=>'form', 'route' => array('cheques.update', 'XXX'), 'class' => 'form' , 'method' => 'PUT')) }}
          <div class="form-group col-sm-6">
              <label>Histórico do Cheque</label>
              <textarea class="form-control" readonly rows="10" type="text" id="historico-not-edit"></textarea>
          </div>
          <div class="form-group col-sm-6">
              <label>Editar Histórico</label>
              <textarea class="form-control" name="historico" rows="10" type="text" id="historico-edit"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button id="update-cheque" type="button" class="btn btn-primary update-cheque" url="">Salvar</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

@stop
