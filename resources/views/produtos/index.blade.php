@extends('layouts.padrao')

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
            "search": {
    "regex": true
  },"rowCallback": function( row, data, index ) {
    if ( data["desabilitar"] == 1 )
    {
        $('td', row).css('background-color', '#f19393');
    }
    else if ( data["desabilitar"] == 0 )
    {
        // $('td', row).css('background-color', 'Orange');
    }
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
             "pageLength": 50,
            "columns": [
                { "data": "id" },
                { "data": "id" },
                { "data": "titulo" },
                { "data": "pessoa" },
                { "data": "categorias" },
                { "data": "preco" },
                { "data": "quantidade_estoque" },
                { "data": "id" },
                { "data": "desabilitar" }
            ],
            "columnDefs":[
              {
                "render":function(data, type, full, row){
                  if(full.pessoa != null){
                    if(full.pessoa.tipo == "j"){
                      return full.pessoa.razao_social;
                    }else if(full.pessoa.tipo == "f"){
                      return (full.pessoa.nome + " " + full.pessoa.sobrenome );
                    }
                  }
                  else return "";
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
                "targets":4
              },
                {
                    "render":function(data, type, full, row){
                      // console.log(full.desabilitar);
                      if(full.desabilitar == 1){
                        return '<a class="btn btn-warning pull-left" href="../produtos/'+data+'/edit/"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> '+
                        '<button class="btn btn-danger pull-left deletar" url="produtos/'+data+'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> '
                        +' <a class="btn btn-primary pull-left" href="../produtos/'+data+'/view/"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>' +
                        '<button class="btn btn-success pull-left habilitar" url="../produtos/'+data+'/habilitar/"><span class="fa fa-check" aria-hidden="true"></span></button>';
                      }else{
                        return '<a class="btn btn-warning pull-left" href="../produtos/'+data+'/edit/"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> '+
                        '<button class="btn btn-danger pull-left deletar" url="produtos/'+data+'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> '
                        +' <a class="btn btn-primary pull-left" href="../produtos/'+data+'/view/"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>' +
                        '<button class="btn btn-purple pull-left desabilitar" url="../produtos/'+data+'/desabilitar/"><span class="fa fa-ban" aria-hidden="true"></span></button>';
                      }
                    },
                    "targets":7
                },
                {
                    "render":function(data, full, type, row){
                      // console.log(row);
                      return data;
                    },
                    "targets":8
                },
                {
                "targets": [ 0,8 ],
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
    regExSearch = "^([A-Z]{"+this.value+"})$";

    // Apply the search
   table.columns().every( function (i) {
       var that = this;

       $( 'input', this.footer() ).on( 'keyup change', function () {
           if ( that.search() !== this.value ) {
             if(i==1 || i==0) {
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
             if(i==1 || i==0) {
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
    } );

    </script>
@stop

@section('content')
<div class="page-header">
  <h1>Produtos <small>Lista</small></h1>
</div>
  <!-- will be used to show any messages -->
@if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif


<div>
  <div id="msgOK" class="alert alert-success" role="alert"></div>
  <div id="msgERRO" class="alert alert-success" role="alert"></div>
<a href="{!!URL::route('produtos.create')!!}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Novo Produto</a>
<div style="clear:both;"></div>
</div>
<hr style="clear:both;" />
  <table id="produtos" class="display table table-striped table-bordered" cellspacing="0" width="100%">
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
            <th>Ações</th>
        </tr>
    </tfoot>
</table>
<hr/>
  <a href="{!!URL::route('produtos.create')!!}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Novo Produto</a>
@stop
