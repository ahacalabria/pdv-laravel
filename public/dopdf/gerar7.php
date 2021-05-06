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
}if(!empty($_GET['com_nota'])){
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

$sql = "SELECT * from imposto";

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

//$sql .= " ORDER BY v.id";

//die($sql);
$query = $mysqli->query($sql);

$pdf->SetFont('arial','B',18);
$pdf->Cell(0,5,utf8_decode("Relatório Geral de Despesas sobre Produto"),0,1,'C');
$pdf->SetFont('arial','',14);
$pdf->Cell(100,35,$query->num_rows." dispesas listadas",0,0,'L');
$data_venda = new DateTime();
$data_venda = $data_venda->format('d/m/Y H:i:s');
$pdf->Cell(440,35,"".$data_venda,0,1,'R');
$pdf->Cell(0,5,"","B",1,'C');

$numResults = $query->num_rows;
$counter = 0;
$valor_all_order = 0;

	$pdf->SetFont('arial','B',10);
	$pdf->Ln(15);

$pdf->SetFont('arial','B',10);
$pdf->Cell(40,15,"#",1,0,'C');
$pdf->Cell(450,15,utf8_decode("Descrição"),1,0,'C');
$pdf->Cell(50,15,"Valor (%)",1,0,'C');

while($row = $query->fetch_assoc()){

	$pdf->Ln(15);
	$pdf->SetFont('arial','',10);
	$pdf->Cell(40,15,$row['id'],1,0,'R');
	$pdf->Cell(450,15,$row['nome'],1,0,'L');
	$pdf->Cell(50,15,"".number_format($row['valor'], 2, ',', '.'),1,0,'R');

	
    }

mysqli_close($mysqli);
$pdf->Output();//"arquivo.pdf","D"
?>
