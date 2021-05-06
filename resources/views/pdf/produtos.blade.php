<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Produtos</title>

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
    <th class="desc"><b>Ieté - Sistema Integrado de Automação Comercial</b></th>
    <th class="desc"><b>Emissão</b></th>
    <th class="desc">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y') }}</th>
  </tr>
  <tr>
    <td class="desc">Relatório Produtos - Geral / Detalhada</td>
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

<div class="footer-b">
    Página <span class="pagenum"></span>
</div>
<?php /**/ $total_lucro_geral = 0 /**/ ?>
<?php /**/ $contador = 0 /**/ ?>
<?php /**/ $contadorb = 0 /**/ ?>
  <?php /**/ $total_preco = 0 /**/ ?>
  <?php /**/ $total_desconto = 0 /**/ ?>
  <?php /**/ $total_custo = 0 /**/ ?>
  <?php /**/ $total_lucro = 0 /**/ ?>

@foreach ($data as $produto)
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

    <th><b>Fornecedor:</b> {{$produto->pessoa->razao_social}}/{{$produto->pessoa->nome_fantasia}}</th>
    <th><b>Data Cadastro:</b>  {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $produto->created_at)->format('d/m/Y') }}</th>

  </tr>
</table>
    </th>
  </tr>
  <tr>
    <td class="tg-031e">

        <table class="tg">
        <thead>
  <tr>
    <th class="tg-031e"><b>Produto</b></th>
    <th class="tg-yw4l"><b>Descrição</b></th>
    <th class="tg-yw4l"><b>Estoque</b></th>
    <th class="tg-yw4l"><b>Preço Venda</b></th>
    <!-- <th class="tg-yw4l"><b> </b></th> -->
    <th class="tg-yw4l"><b>Preço Compra</b></th>
    <th class="tg-yw4l"><b>Lucro</b></th>
  </tr>
  <thead>
  <tbody>


  <?php /**/ $total_preco = $total_preco+$produto->preco /**/ ?>
  <?php /**/ $total_desconto = $total_desconto+$produto->valor_desconto /**/ ?>
  <?php /**/ $total_custo = $total_custo+$produto->custo /**/ ?>
  <?php /**/ $total_lucro = $total_lucro+($produto->preco-$produto->custo) /**/ ?>
  <tr>
    <td class="tg-031e">#{{$produto->id}}</td>
    <td class="tg-yw4l">{{$produto->titulo}}</td>
    <td class="tg-yw4l">{{$produto->quantidade_estoque}}</td>
    <td class="tg-yw4l">R${{number_format($produto->preco, 2, ',', '.')}}</td>
    <!-- <td class="tg-yw4l"></td> -->
    <td class="tg-yw4l">R${{number_format($produto->custo, 2, ',', '.')}}</td>
    <td class="tg-yw4l">R${{number_format($produto->preco-$produto->custo, 2, ',', '.')}}</td>
    <?php /**/ $total_lucro_geral = $total_lucro_geral + ($produto->preco-$produto->custo)/**/ ?>
  </tr>
  </tbody>

</table>

    </td>
  </tr>
@if($contador == count($data))
  <tr>
      <td class="tg-031e">
        <table class="tg">
  <tr>
    <th class="tg-031e"></th>
    <th class="tg-yw4l" style="text-align:right;"><b>Lucro no periodo:</b> R${{$total_lucro_geral}} </th>
  </tr>
</table>
  </tr>
  @endif
</table>
@endforeach

</div>
</div>

  </body>
</html>
