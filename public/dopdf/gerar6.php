<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require("config.php");
if(isset($_GET['qtd_estoque'])){
//	$qtd_estoque = $_GET['qtd_estoque'];
}else{
//	$qtd_estoque = NULL;
}
if(isset($_GET['id_produto'])){
//	$id_produto = $_GET['id_produto'];
}else{
//	$id_produto = NULL;
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
//$pdf->SetMargins(0, 0, 0, 0);
 
//linhas da tabela
$pdf->SetFont('arial','',12);

if($id_produto != NULL){
//echo $qtd_estoque;
//$sql = 'select p.*, u.sigla as sigla from produto as p, unidade u WHERE p.unidade_id = u.id AND p.deleted_at is AND p.id = "'.$id_produto.'"';
}else{
//$sql = 'select p.*, u.sigla as sigla from produto as p, unidade u WHERE p.unidade_id = u.id AND p.deleted_at is NULL ORDER BY titulo';
}
//$result=$mysqli->query($sql);

$prods = $_GET['codigo_produto'];
$qtds = $_GET['quantidade_produto'];
$lista_geral = [];
$produto_temp = NULL;

	$sql = 'select p.*, u.sigla as sigla from produto as p, unidade u WHERE p.unidade_id = u.id AND p.deleted_at is NULL AND p.id IN ('. implode(', ', $prods).')';

	$result=$mysqli->query($sql);
//var_dump($result);
	while($row = $result->fetch_assoc()){
		$produto_temp[] = $row;
	}
		for($y = 0; $y < count($qtds); $y++){
			for($z=0; $z<$qtds[$y]; $z++){
				$lista_geral[] = $produto_temp[$y];	
			}
		}


//var_dump($lista_geral);

$contar_par=0;
$titulo1="";
$titulo2="";
$sigla1="";
$sigla2="";
$preco1=""; 
$preco2="";
$pdf->Cell(550,10,'',0,1,"L");
foreach($lista_geral as $item){
    $contar_par++;
    if(($contar_par%2)!=0){
	$titulo1 = $item['titulo'];//.' askjndaksjndkajsnkjnskajdn';
	$sigla1 = $item['sigla'];//."IDADE";
	$preco1 = $item['preco'];	
    }else{
	$titulo2 = $item['titulo'];
	$sigla2 = $item['sigla'];
	$preco2 = $item['preco'];
	$pdf->Cell(550,4,'',0,1,"L");
	$pdf->SetFont('arial','',9);
	$pdf->MultiCell(220,(strlen($titulo1)>=38 ? 24 : 48),$titulo1,0,"C",0);
	$eixo_x = $pdf->getX();
	$eixo_y = $pdf->getY();
	$pdf->SetXY($eixo_x+220,$eixo_y-48);
	$pdf->Cell(105,48,'',0,0,"L");
	$pdf->MultiCell(220,(strlen($titulo2)>=38 ? 24 : 48),$titulo2,0,"C",0);
	$pdf->SetFont('arial','',14);
	$pdf->Cell(50,20,$sigla1,0,0,"L");
	$pdf->SetFont('arial','',14);
	$pdf->Cell(170,20,"R$ ".str_replace('.', ',', $preco1),0,0,"R");
	$pdf->Cell(120,20,'',0,0,"L");
	$pdf->SetFont('arial','',14);
	$pdf->Cell(50,20,$sigla2,0,0,"L");
	$pdf->SetFont('arial','',14);
	$pdf->Cell(150,20,"R$ ".str_replace('.', ',', $preco2),0,1,"R");
    }

}
mysqli_close($mysqli);
$pdf->Output();//"arquivo.pdf","D"
?>
