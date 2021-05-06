<?php
require("config.php");
if(isset($_GET['qtd_estoque'])){
	$qtd_estoque = $_GET['qtd_estoque'];
}else{
	$qtd_estoque = NULL;
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

if($qtd_estoque != NULL){
$result=$mysqli->query('select * from produto WHERE deleted_at is NULL AND desabilitar = 0 AND quantidade_estoque > "'.$qtd_estoque.'" ORDER BY titulo');
}else{
$result=$mysqli->query('select * from produto WHERE deleted_at is NULL AND desabilitar = 0 ORDER BY titulo');
}


$pdf->SetFont('arial','B',18);
$pdf->Cell(0,5,utf8_decode("Relatório Geral de Produtos"),0,1,'C');
$pdf->SetFont('arial','',14);
$pdf->Cell(10,35,$result->num_rows." produtos listados",0,1,'L');
$pdf->Cell(0,5,"","B",1,'C');
$pdf->Ln(50);
 
//cabe?alho da tabela 
$pdf->SetFont('arial','B',10);
$pdf->Cell(40,20,utf8_decode('Cód.'),1,0,"L");
$pdf->Cell(400,20,'Nome',1,0,"L");
$pdf->Cell(50,20,'Qtd.',1,0,"L");
$pdf->Cell(50,20,utf8_decode('Preço(R$)'),1,1,"L");
//$pdf->Cell(100,20,'Page '.$pdf->PageNo().'/{nb}',0,1,'L');

$pdf->SetFont('arial','',10);
while($row = $result->fetch_assoc()){
    $pdf->Cell(40,20,$row['id'],1,0,"R");
    $pdf->Cell(400,20,$row['titulo'],1,0,"L");
    $pdf->Cell(50,20,$row['quantidade_estoque'],1,0,"R");
    $pdf->Cell(50,20,str_replace('.', ',', $row['preco']),1,1,"R");
}
mysqli_close($mysqli);
$pdf->Output();//"arquivo.pdf","D"
?>
