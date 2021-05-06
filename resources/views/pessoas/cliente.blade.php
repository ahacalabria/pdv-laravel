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
                "url": "/getpessoatipocliente/datatables",
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
                {"data":"nome",
                    "className":"left",
                    "render":function(data, type, full, meta){
                      if(full.tipo=="f"){
                        return full.nome + ' ' + full.sobrenome;
                      }
                       return full.nome_fantasia +' '+ full.razao_social;
                    }
                   },
                { "data": "cpf",
                    "className":"left",
                    "render":function(data, type, full, meta){
                      if(full.tipo=="f"){
                       return full.cpf;
                     }return full.cnpj;
                    }
              },
                { "data": "tipo",
                    "className":"left",
                    "render":function(data, type, full, meta){
                      if(full.tipo=="f"){
                       return "Física";
                   }return "Jurídica";
                    }
                 },
                {"data":"telefone_1",
                    "className":"left",
                    "render":function(data, type, full, meta){
                       return full.telefone_1 +'<br/>'+ full.telefone_2;
                    }
                   },
                { "data": "created_at"  },
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
                    "visible":true,
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
    </script>
@stop

@section('content')
<div class="page-header">
  <h1>Clientes <small>Lista</small></h1>
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
<a href="{!!URL::route('pessoa.create')!!}?tipo=cliente" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Novo Cliente</a>
<div style="clear:both;"></div>
</div>
<hr style="clear:both;" />
  <table id="pessoas" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th><span>#</span></th>
            <th><span>Nome</span></th>
            <th><span>CPF/CPNJ</span></th>
            <th><span>Tipo Pessoa</span></th>
            <th><span>Telefone</span></th>
            <th><span>Criado em</span></th>
            <th><span>Ações</span></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>CPF/CPNJ</th>
            <th>Tipo Pessoa</th>
            <th>Telefone</th>
            <th>Criado em</th>
            <th>Ações</th>
        </tr>
    </tfoot>
</table>
<hr/>
  <a href="{!!URL::route('pessoa.create')!!}?tipo=cliente" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Novo Cliente</a>
@stop