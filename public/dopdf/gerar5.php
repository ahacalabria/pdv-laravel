<?php
require("config.php");

if(!empty($_GET['data_inicio'])){
	$date = str_replace('/', '-', $_GET['data_inicio']);
	$data_inicio = date('Y-m-d', strtotime($date));	
}if(!empty($_GET['data_fim'])){
	$date2 = str_replace('/', '-', $_GET['data_fim']);
	$data_fim = date('Y-m-d', strtotime($date2));	
}if(!empty($_GET['cliente_id'])){
	$cliente_id = $_GET['cliente_id'];
}if(!empty($_GET['vendedor_id'])){
	$vendedor_id = $_GET['vendedor_id'];
}if($_GET['com_nota'] != "" && ($_GET['com_nota'] == 0 || $_GET['com_nota'] == 1)){
	$com_nota = $_GET['com_nota'];
}if(!empty($_GET['id_venda'])){
	$id_venda = $_GET['id_venda'];
}if(!empty($_GET['status'])){
	$status = $_GET['status'];
}

//Connect to your database
//CONFIGURA??ES DO BD MYSQL                               

//CONECTA COM O MYSQL
$mysqli = new mysqli($servidor, $usuario, $senha, $bd);

//$conn   =   mysqli_connect($servidor, $usuario, $senha);
//$db     =   mysqli_select_db($bd, $conn);      


require('fpdf.php');
$pdf= new FPDF("P","pt","A4");
$pdf->AddPage();
 
//linhas da tabela
$pdf->SetFont('arial','',12);

$sql = "SELECT c.nome as cliente,c.razao_social as cliente_f, vend.nome as vendedor, conf.nome as conferente,v.*, v.valor_desconto, v.tipo_desconto, tp.tipo FROM venda v INNER JOIN pessoa c ON v.pessoa_cliente_id = c.id INNER JOIN pessoa vend ON vend.id = v.pessoa_vendedor_id INNER JOIN pessoa conf ON conf.id = v.pessoa_conferente_id INNER JOIN tipo_pagamento tp ON tp.id = v.tipo_pagamento_id WHERE 1 ";

if(isset($id_venda)){
$sql .= " AND v.id = '".$id_venda."'";
}

if(isset($status)){
$sql .= " AND v.status = '".$status."'";
}
if(isset($com_nota)){
$sql .= " AND v.com_nota = '".$com_nota."'";
}
if(isset($cliente_id)){
$sql .= " AND v.pessoa_cliente_id = '".$cliente_id."'";
}
if(isset($vendedor_id)){
$sql .= " AND v.pessoa_vendedor_id = '".$vendedor_id."'";
}

if(isset($data_inicio)){
$sql .= " AND v.created_at >= '".$data_inicio."'";
}
if(isset($data_fim)){
$sql .= " AND v.created_at <= '".$data_fim."'";
}

$sql .= " ORDER BY v.id";

//die($sql);
$query = $mysqli->query($sql);

$pdf->SetFont('arial','B',18);
$pdf->Cell(0,5,utf8_decode("Relatório Resumido de Vendas"),0,1,'C');
$pdf->SetFont('arial','',14);
$pdf->Cell(100,35,$query->num_rows." vendas listadas",0,0,'L');
$data_venda = new DateTime();
$data_venda = $data_venda->format('d/m/Y H:i:s');
$pdf->Cell(440,35,"".$data_venda,0,1,'R');
$pdf->Cell(0,5,"","B",1,'C');
$pdf->Ln(20);

$numResults = $query->num_rows;
$counter = 0;
$valor_all_order = 0;
$cliente="";
while($row = $query->fetch_assoc()){


	
	while($cliente != $row['cliente']){
	$pdf->Ln(35);
	$pdf->SetFont('arial','B',10);
	$pdf->Cell(0,5,"Cliente: ".$row['cliente'].$row['cliente_f'],0,1,'L');
	$pdf->Ln(15);
	$cliente = $row['cliente'];
$pdf->SetFont('arial','B',10);
$pdf->Cell(60,15,utf8_decode("Cód. Venda"),1,0,'C');
$pdf->Cell(110,15,"Vendedor",1,0,'C');
$pdf->Cell(110,15,"Conferente",1,0,'C');
$pdf->Cell(100,15,"Data",1,0,'C');
$pdf->Cell(80,15,"*",1,0,'C');
$pdf->Cell(80,15,"Total (R$)",1,0,'C');
	}


	$valor_all_order += $row['valor_liquido'];
	
	//Header da venda
	$pdf->Ln(15);
	$pdf->SetFont('arial','',10);
	$pdf->Cell(60,15,$row['id'],1,0,'R');
	$pdf->Cell(110,15,$row['vendedor'],1,0,'C');
	$pdf->Cell(110,15,$row['conferente'],1,0,'C');
	$data_venda = DateTime::createFromFormat('Y-m-d H:i:s', $row['data_venda']);
	$data_venda = $data_venda->format('d/m/Y H:i:s');
	$pdf->Cell(100,15,$data_venda,1,0,'C');
	$pdf->Cell(80,15,utf8_decode($row['com_nota']==1 ? "CONTÉM" : "NÃO CONTÉM"),1,0,'C');
	$pdf->Cell(80,15,"".number_format($row['valor_liquido'], 2, ',', '.'),1,0,'R');

	
	//FIM Header da venda
	//$pdf->SetLineWidth(1);
	//$pdf->Cell(0,1,"","B",1,'C');
	//$pdf->SetDrawColor(0,51,102);
    //$pdf->SetFillColor(230,230,0);
    //$pdf->SetTextColor(220,50,50);

	//$sql_produtos = "SELECT  vp.produto_id, vp.titulo, vp.quantidade, vp.preco, vp.subtotal, v.valor_desconto, v.tipo_desconto, tp.tipo FROM venda v INNER JOIN venda_produto vp ON v.id = vp.venda_id INNER JOIN produto p ON vp.produto_id = p.id INNER JOIN tipo_pagamento tp ON tp.id = v.tipo_pagamento_id WHERE vp.venda_id =".$row['id']."";
	//$query_produtos = $mysqli->query($sql_produtos);
	
	// FOTER DA VENDA
	//$pdf->Ln(5);
	//$pdf->Cell(540,5,"Tipo Pagamento: ".$row['tipo'],0,1,'R');
	//$pdf->Ln(5);
	//$pdf->Cell(540,5,"Sub Total: ".number_format($row['valor_total'], 2, ',', '.'),0,1,'R');
	//$pdf->Ln(5);
	//if($row['tipo_desconto'] == 'd'){
	     //$valor_desconto = $row['valor_desconto'];
	    //}elseif($row['tipo_desconto'] == 'p'){
	     //$valor_desconto = ($row['valor_total']*$row['valor_desconto'])/100;
	   //}
	//$pdf->Cell(540,5,"Desconto: ".number_format($valor_desconto, 2, ',', '.'),0,1,'R');
	//$pdf->Ln(5);
	//$pdf->Cell(540,5,"Valor Total: ".number_format($row['valor_liquido'], 2, ',', '.'),0,1,'R');
	//$pdf->Ln(1);
	// FIM FOTER DA VENDA
    }

$pdf->Ln(30);
$pdf->SetFont('arial','B',10);
$pdf->Cell(540,20,"Total de todas as vendas: R$ ".number_format($valor_all_order, 2, ',', '.'),0,1,'R');
mysqli_close($mysqli);
$pdf->Output();//"arquivo.pdf","D"
?>
