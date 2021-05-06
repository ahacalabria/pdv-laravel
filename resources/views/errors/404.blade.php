<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página Não Encontrada - Erro 404</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lato.css') }}">
    <!-- <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css"> -->

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
                color: red;
            }
            .error{
                color: #000;
                font-size: 26px;
            }
        </style>
</head>
    <body>
        <div class="container">
            <div class="content">
                <div class="error"><small>Erro 404!</small></div>
                <div class="title">Página Não Encontrada :(</br>Mas estamos redirecionando...</br></div>
            </div>
        </div>
    </body>
    <!-- <script src="http://code.jquery.com/jquery-2.1.1.min.js"></script> -->
    <script src="{{ asset('assets/js/jquery-2.1.1.min.js') }}"></script>
    <script>
    $(document).ready(function(){
      setTimeout(function () {
        window.location="{{ url()->previous() }}";
      }, 3000);
    });
    </script>
</html>
