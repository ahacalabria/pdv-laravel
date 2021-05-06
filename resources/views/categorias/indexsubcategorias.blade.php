@extends('layouts.padrao')

@section('styles')
    <link href="{{asset('dist/dataTables/css/dataTables.bootstrap.min.css')}}" />
@stop

@section('scripts')
    <script src="{{ asset('dist/dataTables/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('dist/dataTables/js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript" language="javascript" class="init">
    var categoria_id = {{$categoria->id}};
    $(document).ready(function() {
        table = $('#pessoas').DataTable( {
            "ajax": {
                "url": "{{ URL::to('getSubcategoriasByCategoriaId/')}}"+"/"+categoria_id ,
                "dataSrc": ""
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
                { "data":"nome"},
                { "data": "created_at"  },
                { "data": "updated_at"  },
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
                        //return data + 'oi';
                        var dtStart = data;
                        var dtStartWrapper = moment(dtStart);
                        return dtStartWrapper.format('DD/MM/YYYY HH:mm');
                    },
                    "targets":3
                },
                {
                    "render":function(data, type, row){
                         return '<a class="btn btn-warning pull-left" href="../subcategorias/'+data+'/edit/" data-toggle="tooltip" data-placement="top" title="Editar subcategoria"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> ';
                    // '<button class="btn btn-danger pull-left deletar" url="/subcategorias/'+data+'" data-toggle="tooltip" data-placement="top" title="Deletar subcategoria"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>';
                    //+' <a class="btn btn-primary pull-left" href="subcategorias/'+data+'" data-toggle="tooltip" data-placement="top" title="Ver Subsubcategorias"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>';
                    },
                    "targets":4
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
    </script>
@stop

@section('content')
<div class="page-header">
  <h1>Subcategorias <small>{{$categoria->nome}}</small></h1>
</div>
@if(Session::has('message'))
 <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Sucesso!</strong> {{ Session::get('message') }}
      </div>
@endif
<div>
<a href="{!!URL::route('categorias.index')!!}" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Voltar para categorias</a>
<a href="{!!URL::route('subcategorias.create')!!}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Nova subcategoria</a>
<div style="clear:both;"></div>
</div>
<hr style="clear:both;" />
  <table id="pessoas" class="display table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Modificado em</th>
            <th>Criado em</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Modificado em</th>
            <th>Criado em</th>
            <th>Ações</th>
        </tr>
    </tfoot>
</table>
<hr/>
  <a href="{!!URL::route('subcategorias.create')!!}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Nova subcategoria</a>
@stop
