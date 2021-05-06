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
        table = $('#estoques').DataTable( {
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "/estoque/datatables",
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
                { "data": "titulo" },
                { "data": "categorias" },
                { "data": "pessoa.razao_social" },
                { "data": "quantidade_estoque" },
                { "data": "preco" },
                { "data": "id" }
            ],
            "columnDefs":[
              {
                "render":function(data, type, full, row){
                    return (full.pessoa.nome=="" ? full.pessoa.razao_social : full.pessoa.nome);
                },
                "targets":3
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
                "targets":2
              },
              {
                  "render":function(data, type, row){
                      return Number(data).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2});
                  },
                  "targets":5
              },
              {
                "render":function(data, type, full ,row){
                  return '<a class="btn btn-warning pull-left" href="../produtos/'+data+'/edit/"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> '+
                  '<button class="btn btn-danger pull-left deletar" url="produtos/'+data+'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> '
                  +' <a class="btn btn-primary pull-left" href="../produtos/'+data+'/view/"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>';
                },
                "targets":6
              }
            ],
        } );
        
        table.order([0, 'desc']).draw();
    } );
    $('.input-sm').keyup( function () {
      table
      .search(
        jQuery.fn.DataTable.ext.type.search.string( this.value )
      )
      .draw();
    } );
  </script>
@stop

@section('content')
<div class="page-header">
  <h1>Estoque <small>Entrada de produtos/NOTAS</small></h1>
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
<smal>Contagem: {{$total_prod}} itens em estoque</smal>
  <div id="msgOK" class="alert alert-success hidden" role="alert"></div>
  <div id="msgERRO" class="alert alert-danger hidden" role="alert"></div>
  <a style="margin-left: 10px;float:right;" href="{!!URL::route('estoque.create')!!}" class="btn btn-success"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Entrada de produtos por nota</a>
  <a style="margin-left: 10px;float:right;" href="{!!URL::route('produtos.create')!!}" class="btn btn-success"><span class=" glyphicon glyphicon-tag" aria-hidden="true"></span> Entrada de produtos avulso</a>

<div style="clear:both;"></div>
</div>
<hr style="clear:both;" />
<div class="row">
  <table id="estoques" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Título</th>
            <th>Categorias</th>
            <th>Fornecedor</th>
            <th>Quantidade</th>
            <th>Preço</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
          <th>#</th>
          <th>Título</th>
          <th>Categorias</th>
          <th>Fornecedor</th>
          <th>Quantidade</th>
          <th>Preço</th>
          <th>Ações</th>
        </tr>
    </tfoot>
</table>
<hr/>
<a style="margin-left: 10px;float:right;" href="{!!URL::route('estoque.create')!!}" class="btn btn-success"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Entrada de produtos por nota</a>
<a style="margin-left: 10px;float:right;" href="{!!URL::route('produtos.create')!!}" class="btn btn-success"><span class=" glyphicon glyphicon-tag" aria-hidden="true"></span> Entrada de produtos avulso</a>
</div>
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
          <button type="submit" class="btn btn-info print-cupom"><span class="glyphicon glyphicon-print"></span> IMPRIMIR CUPOM NÃO FISCAL</button>
        </div>
      </div>
    </div>
  </div>


@stop
