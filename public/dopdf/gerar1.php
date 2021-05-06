<?php
require("config.php");

if(!empty($_GET['data_ini'])){
	$date_ini = DateTime::createFromFormat('d/m/Y', $_GET['data_ini']);
	$data_ini = $date_ini->format('Y-m-d');
}if(!empty($_GET['data_fim'])){
	$date_fim = DateTime::createFromFormat('d/m/Y', $_GET['data_fim']);
	$data_fim = $date_fim->format('Y-m-d');
}if(!empty($_GET['produto_id'])){
	$produto_id = $_GET['produto_id'];
}

//Connect to your database
//CONFIGURACOES DO BD MYSQL                               

//CONECTA COM O MYSQL
$mysqli = new mysqli($servidor, $usuario, $senha, $bd);

//$conn   =   mysqli_connect($servidor, $usuario, $senha);
//$db     =   mysqli_select_db($bd, $conn);      


require('fpdf.php');
$pdf= new FPDF("P","pt","A4");
$pdf->AddPage();
 
//linhas da tabela
$pdf->SetFont('arial','',12);

$sql = "SELECT p.id as cod_produto, p.titulo as descricao, p.quantidade_estoque as qtde_estoque, m.* FROM movimentacao m INNER JOIN produto_movimentacao pm ON m.id = pm.movimentacao_id INNER JOIN produto p ON pm.produto_id = p.id WHERE p.deleted_at is NULL AND p.desabilitar = 0 ";

if(isset($produto_id)){
$sql .= " AND p.id = '".$produto_id."'";
}
if(isset($data_ini)){
$sql .= " AND m.created_at >= '".$data_ini."'";
}
if(isset($data_fim)){
$sql .= " AND m.created_at <= '".$data_fim."'";
}

$sql .= " ORDER BY cod_produto";

//die($sql);
$query = $mysqli->query($sql);

$pdf->SetFont('arial','B',18);
$pdf->Cell(0,5,utf8_decode("Relatório de Movimentação de Produtos"),0,1,'C');
$pdf->SetFont('arial','',14);
$pdf->Cell(10,35,utf8_decode($query->num_rows." movimentações listadas"),0,1,'L');
$pdf->Cell(0,5,"","B",1,'C');
$pdf->Ln(30);
 
$cod_prod = "";
$numResults = $query->num_rows;
$counter = 0;
$pdf->SetFont('arial','',10);
while($row = $query->fetch_assoc()){

	if($cod_prod == "" || $row['cod_produto'] == $cod_prod){
	if($cod_prod == "") {
$lastElement = end($row);
$pdf->Ln(20);
$pdf->SetFont('arial','B',12);
$pdf->Cell(0,5,$row['cod_produto'] ." - ". $row['descricao'],0,1,'C');
$pdf->Ln(20);
//cabe?alho da tabela 
$pdf->SetFont('arial','B',10);
$pdf->Cell(100,20,'Movimento',1,0,"C");
$pdf->Cell(70,20,'Referencial',1,0,"C");
$pdf->Cell(100,20,'Detalhes',1,0,"C");
$pdf->Cell(60,20,'V. Un.(R$)',1,0,"C");
$pdf->Cell(70,20,'V. Total(R$)',1,0,"C");
$pdf->Cell(70,20,'Quantidade',1,0,"C");
$pdf->Cell(70,20,'Estoque',1,1,"C");
$qtde_estoque = $row['qtde_estoque'];
	}
	$pdf->SetFont('arial','',10);
	$data_movimentacao = DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']);
	$data_movimentacao = $data_movimentacao->format('d/m/Y H:i:s');
    $pdf->Cell(100,20,$data_movimentacao,1,0,"R");
    $pdf->Cell(70,20,$row['numero_nota'],1,0,"R");
    $pdf->Cell(100,20,$row['emitente_destinatario'],1,0,"R");
    $pdf->Cell(60,20,number_format($row['valor_unitario'], 2, ',', '.'),1,0,"R");
    $pdf->Cell(70,20,number_format($row['valor_total'], 2, ',', '.'),1,0,"R");
    $pdf->Cell(70,20,$row['quantidade'],1,0,"R");
    $pdf->Cell(70,20,$row['estoque'],1,1,"R");
	$cod_prod = $row['cod_produto'];
	$qtde_estoque = $row['qtde_estoque'];
	}else{
$pdf->Cell(540,20,"Estoque atual: ".$qtde_estoque,0,1,'R');
$pdf->Ln(20);
$pdf->SetFont('arial','B',12);
$pdf->Cell(0,5,$row['cod_produto'] ." - ". $row['descricao'],0,1,'C');
$pdf->Ln(20);
//cabe?alho da tabela 
$pdf->SetFont('arial','B',10);
$pdf->Cell(100,20,'Movimento',1,0,"C");
$pdf->Cell(70,20,'Referencial',1,0,"C");
$pdf->Cell(100,20,'Detalhes',1,0,"C");
$pdf->Cell(60,20,'V. Un.(R$)',1,0,"C");
$pdf->Cell(70,20,'V. Total(R$)',1,0,"C");
$pdf->Cell(70,20,'Quantidade',1,0,"C");
$pdf->Cell(70,20,'Estoque',1,1,"C");
$pdf->SetFont('arial','',10);
	$data_movimentacao = DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']);
	$data_movimentacao = $data_movimentacao->format('d/m/Y H:i:s');
    $pdf->Cell(100,20,$data_movimentacao,1,0,"R");
    $pdf->Cell(70,20,$row['numero_nota'],1,0,"R");
    $pdf->Cell(100,20,$row['emitente_destinatario'],1,0,"R");
    $pdf->Cell(60,20,number_format($row['valor_unitario'], 2, ',', '.'),1,0,"R");
    $pdf->Cell(70,20,number_format($row['valor_total'], 2, ',', '.'),1,0,"R");
    $pdf->Cell(70,20,$row['quantidade'],1,0,"R");
    $pdf->Cell(70,20,$row['estoque'],1,1,"R");
	$cod_prod = $row['cod_produto'];
	$qtde_estoque = $row['qtde_estoque'];
	}

if (++$counter == $numResults) {
	$pdf->Cell(540,20,"Estoque atual: ".$qtde_estoque,0,1,'R');
    }
	if(count($row) == $lastElement){

	}
}
mysqli_close($mysqli);
$pdf->Output();//"arquivo.pdf","D"
?>
