<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('faveicon.ico') }}">
    <title>{{ $titulo or 'PDV IETÉ' }}</title>

     <!-- Fonts & Styles-->
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/chosen/chosen.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/lato.css') }}"> -->
     <!-- bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!--Dynamic StyleSheets added from a view would be pasted here-->
    @yield('styles')
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style media="screen">
      .chosen-container-single .chosen-single{
        height: 35px!important;
      }
      .chosen-container{
        width: 100%!important;
      }
      .chosen-choices{
        max-height:80px;
        overflow: auto;
      }
      .scroll-tablet{
        max-height: 50px!important;
      }
    </style>
    <script type="text/javascript">
        $urlserver = '{{ url('/') }}';
        @if(Auth::user()!=NULL) $level = '{{ Auth::user()->level }}'; @endif;

        function handleAjaxError( xhr, textStatus, error ) {
          if ( textStatus === 'timeout' ) {
            alert( 'O servidor está demorando para responder, por favor recarregar a página!' );
          }
          else {
            console.log( 'O servidor encontrou um erro, por favor recarregar a página!' );
          }
          table.fnProcessingIndicator( false );
        }
    </script>
</head>
    <body>
        @include('templates.menu')
        <div class="container">
                 <div class="main">
                        @yield('content')
                </div>
        </div>
        <footer class="footer">
            <div class="container">
            <div class="col-md-9">
            <p><b>Você está em um ambiente seguro.</b></p>
            <p><b>Todos os seus dados estão protegidos.</b></p>
            <p>© 2019 Ieté Aplicações e Sistemas Web. Todos os direitos reservados.</p>
            </div>
            <div class="col-md-3 desenvolvedora">
                <img height="50px" src="{{ asset('img/iete-logo.png') }}">
            </div>
            </div>
        </footer>
        <!-- Modal -->
<div class="modal fade" id="modal-pesquisar-prod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Pesquisar Produto</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="form-group col-sm-2">
              {!! Form::label('Código') !!}
              <input id="codprodmodal" class="form-control" type="text" placeholder="Código">
          </div>
        <div class="form-group col-sm-8">
            {!! Form::label('Descrição produto') !!}<br>
            <select id="select_modal" class="form-control"></select></br></br>
        </div>
        <div class="form-group col-sm-2">
          {!! Form::label('Ação') !!}
        <button id="detalharprod" type="button" class="btn btn-primary">Detalhar</button>
      </div>
      <div id="prod-space-modal" class="col-sm-12" style="min-height: 200px">

      </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
        <!-- <div class="bt-venda"><a href="{{ url('/vendas') }}"><span class="btn btn-xs btn-success">VENDAS</span></a> <a href="{{ url('/') }}"><span class="btn btn-xs btn-primary bt-menuu" style="display:none;">MENU</span></a></div> -->
        <div style="position: fixed;top: 30%;width:45px;z-index:5;right: 0px;">
        <button id="atalho-nova-venda" class="btn btn-lg btn-success" url="{{ url('/vendas/create') }}" style="width:45px;margin-bottom: 5px;"><i class="glyphicon glyphicon-shopping-cart"></i></button>
        <button class="btn btn-lg btn-warning" id="btn-open-search-modal" style="width:45px;margin-bottom: 5px;"><i class="glyphicon glyphicon-search"></i></button>
        <button id="atalho-ver-vendas" class="btn btn-lg btn-primary" url="{{ url('/vendas') }}" style="width:45px;margin-bottom: 5px"><i class="glyphicon glyphicon-list-alt"></i></button>
        <button id="atalho-novo-cliente" class="btn btn-lg btn-gold" url="{{ url('/pessoa/create?tipo=cliente') }}" style="width:45px"><i class="fa fa-user-plus"></i></button>
      </div>

        <!-- SCRIPTS -->
        <script src="{{ asset('assets/js/jquery-2.1.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/3.bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/select2.min.js') }}"></script>
        <script src="{{ asset('assets/chosen/chosen.jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/moment-with-locales.js') }}"></script>
        <script src="{{ asset('assets/js/autoNumeric.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.hotkeys.js') }}"></script>
        @yield('scripts')
        <script src="{{ asset('dist/dataTables/js/accent-neutralise.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>
        <script type="text/javascript">
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                if($(".page-header.vendas").length){
                     $('.navbar-fixed-top').css('display','none');
                    //  $('.bt-menuu').css('display','inline-block');
                    //  $('body').css('padding-top','90px');
                     $('.page-header.vendas').css('display','none');
                    //  $('.navbar-fixed-top.vendas').css('display','none');
                     $('.barrinha-prod').css('position','fixed');
                     $('.barrinha-prod').css('top','0');
                     $('.barrinha-prod').css('padding-top','15px');
                     $('.barrinha-prod').css('left','0');
                     $('.inserir_item').html('<span class="glyphicon glyphicon-plus"></span>');
                    //  $('.barrinha-prod').css('max-width','860px');
                    // $('.ppt').css('padding-top','100px');
                     $('.barrinha-prod').css('width','100%');
                     $('.barrinha-prod label').css('display','none');
                     $("#formulario > div.col-sm-12.barrinha-prod > div.form-group.col-sm-2.colunac3 > br, #formulario > div.col-sm-12.barrinha-prod > div:nth-child(3) > br").remove();//remover br chato do label adicionar item
                    //  $('.barrinha-prod').css('margin-left','-35px');
                     $('.barrinha-prod').css('margin-right', 'auto');
                     $('.barrinha-prod').css('background','#CCC');
                     $('.barrinha-prod').css('z-index', '9');
                     $("#formulario > .chosen-container .chosen-results").addClass('scroll-tablet');
                    //  $('.chosen-container .chosen-results').css('max-height','50px!important');
                     }

                    //  $('.chosen-container.chosen-container-single, .chosen-single, chosen-drop').css('z-index', '10');

                    //  $("#formulario").css('margin-top', ($('.barrinha-prod').height())+"px");

                    //  $("select.form-control.produtos_descricao_only").css('z-index','5').select2();
                    if(window.location.href.endsWith("vendas/create")
                      || window.location.href.endsWith("pessoa/create?tipo=cliente")){
                      window.onbeforeunload = function() {
                          return "Você tem certeza que deseja recarregar a página?";
                      }
                    }

                    if(window.location.href.endsWith("edit/")){
                      window.onbeforeunload = function() {
                          return "Você tem certeza que deseja recarregar a página?";
                      }
                    }
                 }
        </script>
    </body>
</html>
