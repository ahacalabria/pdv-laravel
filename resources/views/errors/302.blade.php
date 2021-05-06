<!DOCTYPE html>
<html>
    <head>
        <title>Acesso Não Permitido - Erro 302</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #6200ea;
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
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="error"><small>Erro 302!</small></div>
                <div class="title">Permissão Não Concedida :(</br>Mas estamos redirecionando...</br></div>
            </div>
        </div>
    </body>
    <script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script>
    $(document).ready(function(){
      setTimeout(function () {
        window.location="{{ url()->previous() }}";
      }, 3000);
    });
    </script>
</html>
