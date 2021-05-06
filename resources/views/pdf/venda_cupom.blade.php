<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Vendas</title>

    <link rel="stylesheet" type="text/css" href="assets/css/pdf.css">
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <style type="text/css">
        body{
            width: 100%;
            margin:0;
            padding:0;
            margin-top: 100px;
        }
    </style>
  </head>
  <body>
  <table class="header headers"  border="0" cellspacing="0" cellpadding="0">
  <tbody>
  <tr>
    <th class="desc"><b>GS DISTRIBUIDOR CL</b></th>
    <th class="desc"><b>Emissão:</b></th>
    <th class="desc">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y') }}</th>
  </tr>
  <tr>
    <td class="desc">Juazeiro do Norte - Ceará</td>
    <td class="desc"><b>Hora:</b></td>
    <td class="desc">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('H:i:s') }}</td>
  </tr>
  <tr>
    <td class="desc">

    <!-- </td>
      <td class="desc"> -->
        Fone: (88) 9 9604-2782 / (88) 9 9671-3214
      </td>
  </tr>
  </tbody>
</table>

<div class="footer-b">
    Página <span class="pagenum"></span>
</div>
<?php /**/ $total_lucro_geral = 0 /**/ ?>
<?php /**/ $contador = 0 /**/ ?>
<?php /**/ $contadorb = 0 /**/ ?>
<?php /**/ $contador2 = 0 /**/ ?>
@foreach ($data as $venda)
<?php /**/ $contador++ /**/ ?>
<?php /**/ $contadorb++ /**/ ?>

@if($contadorb == 1)
<div class="container">
<div class="conteudos">
@endif
<table class="tg">
  <tr>
    <th class="tg-031e" style="text-align:left;">
    <table>
  <tr>
    <th><b>Código:</b> {{$venda->id}}</th>
    <th><b>Data:</b> {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $venda->data_venda)->format('d/m/Y H:i:s') }} </th>
    <th><b>Cliente:</b> {{$venda->cliente->nome.$venda->cliente->razao_social}} {{$venda->cliente->sobrenome}} | @if($venda->com_nota) <small>CONTÊM</small> @endif @if(!$venda->com_nota) <small>NÃO CONTÊM</small> @endif</th>
  </tr>
  <tr>
    <th class="tg-yw4l"><b>Vendedor:</b> {{$venda->vendedor->nome}}</th>
    <th class="tg-yw4l"><b>Conferente:</b> {{$venda->conferente->nome}}</th>
    <th class="tg-yw4l"><b>Total de Itens:</b> {{count($venda->produtos)}}</th>
  </tr>
</table>
    </th>
  </tr>
  <tr>
    <!-- <td class="tg-031e"> -->

        <table class="tg">
        <thead>
  <tr>
    <th class="tg-yw4l" style="text-align:right;"><b>#</b></th>
    <th class="tg-yw4l" style="text-align:right;"><b>Código</b></th>
    <th class="tg-yw4l"><b>Descrição</b></th>
    <th class="tg-yw4l" style="text-align:right;"><b>Qtd.</b></th>
    <th class="tg-yw4l" style="text-align:right;"><b>Valor UN. (R$)</b></th>
    <th class="tg-yw4l" style="text-align:right;"><b>Sub Total (R$)</b></th>
    <!-- <th class="tg-yw4l"><b>Preço Compra</b></th>
    <th class="tg-yw4l"><b>Agregado</b></th> -->
  </tr>
</thead>
  <tbody>

  <?php /**/ $total_preco = 0 /**/ ?>
  <?php /**/ $total_desconto = 0 /**/ ?>
  <?php /**/ $total_custo = 0 /**/ ?>
  <?php /**/ $total_lucro = 0 /**/ ?>
  @foreach ($venda->produtos as $key=>$produto)
<?php /**/ $contador2++ /**/ ?>
  <?php /**/ $total_preco = $total_preco+$produto->pivot->preco /**/ ?>
  <?php /**/ $total_desconto = $total_desconto+$produto->valor_desconto /**/ ?>
  <?php /**/ $total_custo = $total_custo+$produto->custo /**/ ?>
  <?php /**/ $total_lucro = $total_lucro+($produto->pivot->preco-$produto->custo); /**/
  $qtd_prod = number_format($produto->pivot->quantidade, 2, ',', '.');
  ?>
  <tr>
    <td class="tg-yw4l" style="text-align:right;">{{$key+1}}</td>
    <td class="tg-yw4l" style="text-align:right;">{{$produto->id}}</td>
    <td class="tg-yw4l">{{$produto->titulo}}</td>
    <td class="tg-yw4l" style="text-align:right;">{{$qtd_prod}}</td>
    <td class="tg-yw4l" style="text-align:right;">{{number_format($produto->pivot->preco, 2, ',', '.')}}</td>
    <td class="tg-yw4l" style="text-align:right;">{{number_format($produto->pivot->subtotal, 2, ',', '.')}}</td>

  </tr>
  @endforeach
  <!-- </table> -->
  <tbody class="tg">
  <tr>
    <?php
    if($venda->tipo_desconto == 'd'){
     $valor_desconto = $venda->valor_desconto;
    }elseif($venda->tipo_desconto == 'p'){
     $valor_desconto = ($venda->valor_total*$venda->valor_desconto)/100;
   }
   ?>
   <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l" style="text-align:right;"><b>Tipo Pagamento: </b> {{$venda->tipo_pagamento->tipo}}</th>
  </tr>
  <tr>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l" style="text-align:right;"><b>Sub Total: </b> R$ {{number_format($venda->valor_total, 2, ',', '.')}}</th>
  </tr>
  <tr>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l" style="text-align:right;"><b>Desconto: </b> R$ {{number_format($valor_desconto, 2, ',', '.')}}</th>
  </tr>
  <tr>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l"></th>
    <th class="tg-yw4l" style="text-align:right;"><b>Total: </b> R$ {{number_format($venda->valor_liquido, 2, ',', '.')}}</th>
  </tr>
  </tbody>
</table>


<!-- </table> -->
@endforeach

</div>
</div>

  </body>
</html>
