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
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/lato.css') }}"> -->
     <!-- bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!--Dynamic StyleSheets added from a view would be pasted here-->
    @yield('styles')
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <script type="text/javascript">
        $urlserver = '{{ url('/') }}';
    </script>
</head>
    <body>
        @include('templates.menu')
        <div class="container" style="width:90%;">
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
        <div class="bt-venda"><a href="{{ url('/vendas') }}"><span class="btn btn-xs btn-success">VENDAS</span></a></div>
        <!-- SCRIPTS -->
        <script src="{{ asset('assets/js/jquery-2.1.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/3.bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/select2.min.js') }}"></script>
        <script src="{{ asset('assets/js/moment-with-locales.js') }}"></script>
        <script src="{{ asset('assets/js/autoNumeric.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.hotkeys.js') }}"></script>
        @yield('scripts')
        <script src="{{ asset('dist/dataTables/js/accent-neutralise.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>
    </body>
</html>
