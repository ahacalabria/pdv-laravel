<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Clientes</title>

    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css">
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <style type="text/css">
        body{
            width: 100%;
            margin:0;
            padding:0;
        }
    </style>
  </head>
  <body>



  <table class="header headers"  border="0" cellspacing="0" cellpadding="0">
  <tbody>
  <tr>
    <th class="desc"><b>Ieté - Sistema Integrado de Automação Comercial</b></th>
    <th class="desc"><b>Emissão</b></th>
    <th class="desc">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y') }}</th>
  </tr>
  <tr>
    <td class="desc">Listagem Clientes - Geral / Detalhada</td>
    <td class="desc">Página</td>
    <td class="desc"><span class="pagenum"></span></td>
  </tr>
  <tr>
    <td class="desc">
GS DISTRIBUIDOR CL<br>R. Sao Paulo, 711 - Centro<br>J. do Norte, Fone: (88) 3512-3931
    </td>
    <td class="desc"><b>Hora</b></td>
    <td class="desc">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('H:i:s') }}</td>
  </tr>
  </tbody>
</table>

<?php /**/ $contador = 0 /**/ ?>
<?php /**/ $contadorb = 0 /**/ ?>
@foreach ($data as $cliente)
<?php /**/ $contador++ /**/ ?>
<?php /**/ $contadorb++ /**/ ?>

@if($contadorb == 1)
<div class="container">
<div class="conteudos">
@endif


    <table class="tg">
  <tr>
    <th class="tg-9hbo"><b>Cliente:</b> {{ $cliente->nome }} {{ $cliente->sobrenome }}</th>
    <th class="tg-yw4l"><b>Cadastro:</b> {{ $cliente->created_at->format('d/m/Y') }}</th>
    <th class="tg-yw4l"></th>
  </tr>
  <tr>
    <td class="tg-yw4l"><b>CPF/CNPJ:</b> {{ $cliente->cpf }} {{ $cliente->cnpj }}         <b>RG:</b> {{ str_limit($cliente->rg, 10) }}</td>
    <td class="tg-yw4l"><b>Nascimento:</b> {{ \Carbon\Carbon::createFromFormat('Y-m-d', $cliente->data_nascimento)->format('d/m/Y') }}</td>
    <td class="tg-yw4l"><b>Sexo: </b> @if($cliente->sexo == 'f') Feminino @endif @if($cliente->sexo == 'm') Masculino @endif</td>
  </tr>
  <tr>
    <td class="tg-9hbo"><b>Endereço:</b> {{ $cliente->endereco }}</td>
    <td class="tg-yw4l"><b>Cidade:</b> {{ $cliente->cidade->nome }}</td>
    <td class="tg-yw4l"><b>Estado:</b> {{ $cliente->estado->nome }}</td>
  </tr>
  <tr>
    <td class="tg-9hbo"><b>Bairro:</b> {{ $cliente->bairro }}</td>
    <td class="tg-yw4l"><b>Fone:</b> {{ $cliente->telefone_1 }} @if($cliente->telefone_2) / {{ $cliente->telefone_2 }}@endif</td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-9hbo"><b>E-mail:</b> E-MAIL DO CLIENTE</td>
    <td class="tg-yw4l"><b>Celular:</b></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-9hbo"><b>Empresa:</b> EMRPESA DO CLIENTE</td>
    <td class="tg-yw4l"><b>Profissão:</b></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-9hbo"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
</table>
@if($contador%5 == 0 )
</div>
</div>
<div class="footers">
    Página <span class="pagenum"></span>
</div>
<div class="page-break"></div>
@elseif(count($data) == 1)
</div>
</div>
<div class="footers">
    Página <span class="pagenum"></span>
</div>
@endif
@if($contadorb ==5)
<?php /**/ $contadorb = 0 /**/ ?>
@endif
@endforeach

  </body>
</html>
