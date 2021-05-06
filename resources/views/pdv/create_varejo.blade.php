@extends('layouts.padraob')
@section('styles')
    <link href="{{asset('dist/dataTables/css/dataTables.bootstrap.min.css')}}" />
    <style type="text/css">
      #produtos_filter{
        display: none;
      }
    </style>
@stop

@section('scripts')
<script type="text/javascript">
  var table;
</script>
    <script src="{{ asset('dist/dataTables/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('dist/dataTables/js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {
        table = $('#produtos').DataTable( {
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "/produtos/datatables",
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
                { "data": "id" },
                { "data": "titulo" },
                { "data": "pessoa" },
                { "data": "categorias" },
                { "data": "preco" },
                { "data": "quantidade_estoque" },
                { "data": "id" }
            ],
            "columnDefs":[
              {
                "render":function(data, type, full, row){
                    return (full.pessoa.nome=="" ? full.pessoa.razao_social : full.pessoa.nome);
                },
                "targets":3,
                "visible": false
              },
              {
                "render":function(data, type, full, row){
                  var i=0;
                  var temp = "";
                  for(i=0;i<data.length;i++){
                    if(i==0) temp+=data[i].nome;
                    else temp+=", "+data[i].nome;
                  }
                    return temp;
                },
                "targets":4
              },
                {
                    "render":function(data, type, row){
                        return '<a class="btn btn-warning pull-left" href="../produtos/'+data+'/edit/"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> '+
                        '<button class="btn btn-danger pull-left deletar" url="produtos/'+data+'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> '
                        +' <a class="btn btn-primary pull-left" href="../produtos/'+data+'/view/"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>';
                    },
                    "targets":7,
                    "visible": false
                },
                {
                "targets": [ 0 ],
                "visible": false
            },
                {
                    "render":function(data, type, row){
                        return Number(data).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2});
                    },
                    "targets":5
                }
            ],


        } );

        $('.input-sm').keyup( function () {
      table
      .search(
        jQuery.fn.DataTable.ext.type.search.string( this.value )
      )
      .draw();
    } );
    // Setup - add a text input to each footer cell
    $('table#produtos tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<label>'+title+'</label><input type="text" style="width: 100%;" placeholder="Buscar '+title+'" />' );
    } );

    $('table#produtos thead th').each( function () {
        var title = $(this).text();
        $(this).html( '<label>'+title+'</label><input type="text" style="width: 100%;" placeholder="Buscar '+title+'" />' );
    } );

    // Apply the search
   table.columns().every( function () {
       var that = this;

       $( 'input', this.footer() ).on( 'keyup change', function () {
           if ( that.search() !== this.value ) {
               that
                   .search( this.value )
                   .draw();
           }
       } );
       $( 'input', this.header() ).on( 'keyup change', function () {
           if ( that.search() !== this.value ) {
               that
                   .search( this.value )
                   .draw();
           }
       } );
   } );

    } );

    </script>
@stop
@section('content')

@if($errors->has())
   @foreach ($errors->all() as $error)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Erro!</strong> {{ $error }}
      </div>
  @endforeach
@endif
<style type="text/css">
  thead, thead tr, thead tr th{
    background-color: #428BCA;
    color: #FFF;
    text-align: center;
    border-color: #428BCA !important;
  }
  .table{
    margin-bottom: 0 !important;
  }
  .footertotal{
    background-color: #FFF;
    margin-right: 2px;
    margin-left: 2px;
    padding: 6px;
  }
  .borda-topo{
    border-top: 1px solid #CCC;
  }
    .totalpagar{
    background-color: #000;
    margin-right: 2px;
    margin-left: 2px;
    padding: 6px;
    color: #FFF;
  }
  
</style>
<div class="row">
  <div class="col-md-4">
    <div class="well well-sm">
    <div class="input-group"> 
      <select class="form-control js-example-basic-single js-example-responsive" id="clientes" name="pessoa_cliente_id"></select> 
        <div class="input-group-btn"> 
          <button type="button" class="btn btn-default" aria-label="Help">
            <span class="glyphicon glyphicon-pencil"></span>
          </button> 
          <button type="button" class="btn btn-default" aria-label="Help">
            <span class="glyphicon glyphicon-eye-open"></span>
          </button> 
          <button type="button" class="btn btn-default">
            <span class="glyphicon glyphicon-plus"></span>
          </button> 
        </div> 
    </div>

    <input class="form-control" aria-label="Text input with multiple buttons"> 
    <table class="table table-bordered table-responsive"> 
      <thead> 
        <tr> 
          <th>Produto</th> 
          <th>Preço</th> 
          <th>Qtd.</th> 
          <th>Subtotal</th> 
        </tr> 
      </thead> 
      <tbody> 
        <tr> 
          <th scope="row">1</th> 
          <td>Mark</td> 
          <td>Otto</td> 
          <td>@mdo</td> 
        </tr> 
        <tr> 
          <th scope="row">2</th> 
          <td>Jacob</td> 
          <td>Thornton</td> <td>@fat</td> 
        </tr> 
        <tr> 
          <th scope="row">3</th> 
          <td>Larry</td> 
          <td>the Bird</td> 
          <td>@twitter</td> 
        </tr> 
      </tbody> 
    </table>
    <div class="row footertotal">
      <div class="col-md-3">
        Itens:
      </div>
      <div class="col-md-3 text-right">0(0)</div>
      <div class="col-md-3">Total:</div>
      <div class="col-md-3 text-right">R$ 0,00</div>
      </div>
       <div class="row footertotal borda-topo">
      <div class="col-md-3">
        Taxa:
      </div>
      <div class="col-md-3 text-right">0(0)</div>
      <div class="col-md-3">Desconto:</div>
      <div class="col-md-3 text-right">R$ 0,00</div>
      </div>
      <div class="row totalpagar">
      <div class="col-md-6">Total a ser pago:</div>
      <div class="col-md-6 text-right">R$ 0,00</div>
      </div>
      <button type="button" class="btn btn-success btn-lg btn-block">Finalizar Venda</button>
    </div>
  </div>
  <div class="col-md-8">
    <div class="well well-sm">
    <table id="produtos" class="display table table-striped table-bordered table-responsive" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th><span>#</span></th>
            <th><span>Codigo</span></th>
            <th><span>Título</span></th>
            <th><span>Fornecedor</span></th>
            <th><span>Categorias</span></th>
            <th><span>Preço</span></th>
            <th><span>Qtd.Estoque</span></th>
            <th><span>Ações</span></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Codigo</th>
            <th>Título</th>
            <th>Fornecedor</th>
            <th>Categorias</th>
            <th>Preço</th>
            <th>Qtd.Estoque</th>
            <th>Ações</th>
        </tr>
    </tfoot>
</table>
    </div>
  </div>
</div>

@endsection
