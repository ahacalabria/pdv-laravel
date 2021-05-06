@extends('layouts.padrao')

@section('styles')
    <link href="{{asset('dist/dataTables/css/dataTables.bootstrap.min.css')}}" />
@stop

@section('scripts')
    <script src="{{ asset('dist/dataTables/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('dist/dataTables/js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" language="javascript" class="init">
    $(document).ready(function() {
        table = $('#pessoas').DataTable( {
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "/getpessoatipofornecedor/datatables",
                // "dataSrc": "",
                "error": handleAjaxError
            },
            "order": [[ 0, "desc" ]],
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
                {"data":"nome",
                    "className":"left",
                    "render":function(data, type, full, meta){
                       return full.nome + ' ' + full.sobrenome + ' ' + full.nome_fantasia +' '+ full.razao_social;
                    }
                   },
                { "data": "cnpj",
                "className":"left",
                  "render":function(data, type, full, meta){
                     return full.cnpj +''+ full.cpf;
                  }
               },
                { "data": "email" },
                {"data":"telefone_1",
                    "className":"left",
                    "render":function(data, type, full, meta){
                       return full.telefone_1 +'<br/>'+ full.telefone_2;
                    }
                   },
                { "data": "created_at"  },
                { "data": "id" },
                { "data": "sobrenome" },
                { "data": "nome_fantasia" },
                { "data": "razao_social" },
            ],
            "columnDefs":[
                {
                    "render":function(data, type, row){
                        var dtStart = data;
                        var dtStartWrapper = moment(dtStart);
                        return dtStartWrapper.format('DD/MM/YYYY HH:mm');
                    },
                    "targets":5
                },
                {
                    "render":function(data, type, row){
                      return '<a class="btn btn-warning pull-left" href="../pessoa/'+data+'/edit/"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>  '+
                      '<button class="btn btn-danger pull-left deletar" url="/pessoa/'+data+'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> '+
                       ' <a class="btn btn-primary pull-left" href="../pessoas/'+data+'/view/"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>';
                    },
                    "targets":6
                },
                {
                    "visible":false, "targets": [3]
                },
                {
                "targets": [ 7 ],
                "visible": false
                },
                {
                    "targets": [ 8 ],
                    "visible": false
                },
                {
                    "targets": [ 9 ],
                    "visible": false
                }
            ],

        } );
        // Setup - add a text input to each footer cell
    $('table#pessoas tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<label>'+title+'</label><input type="text" style="width: 100%;" placeholder="Buscar '+title+'" />' );
    } );

    $('table#pessoas thead th').each( function () {
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
  <h1>Fornecedores <small>Lista</small></h1>
</div>
@if(Session::has('message'))
 <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Sucesso!</strong> {{ Session::get('message') }}
      </div>
@endif
<div>
<a href="{!!URL::route('pessoa.create')!!}?tipo=fornecedor" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Novo Fornecedor</a>
<div style="clear:both;"></div>
</div>
<hr style="clear:both;" />
  <table id="pessoas" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>CNPJ/CPF</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>Criado em</th>
            <th>Ações</th>
            <th>Sobrenome</th>
            <th>Nome Fantasia</th>
            <th>Razão Social</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>CNPJ/CPF</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>Criado em</th>
            <th>Ações</th>
            <th>Sobrenome</th>
            <th>Nome Fantasia</th>
            <th>Razão Social</th>
        </tr>
    </tfoot>
</table>
<hr/>
  <a href="{!!URL::route('pessoa.create')!!}?tipo=fornecedor" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Novo Fornecedor</a>
@stop
