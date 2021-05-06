<?php
require("config.php");
$sql = 'SELECT p.*, e.uf, c.nome as cidade_nome FROM pessoa as p INNER JOIN estados as e INNER JOIN cidades as c ON p.cidade_id = c.id AND e.id = p.estado_id WHERE tipo_cadastro = "cliente" AND p.deleted_at is NULL';
if(!empty($_GET['estado_id'])){
	$sql.= ' AND p.estado_id = '.$_GET['estado_id'];
}
if(!empty($_GET['cidade_id']) && $_GET['cidade_id']!="null"){
	$sql.= ' AND p.cidade_id = '.$_GET['cidade_id'];
}
if(!empty($_GET['data_inicio'])){
	$new_date = explode("/",$_GET['data_inicio']);
	$sql.= ' AND p.created_at >= "'.$new_date[2]."-".$new_date[1]."-".$new_date[0].'"';
}
if(!empty($_GET['data_fim'])){
	$new_date = explode("/",$_GET['data_fim']);
	$sql.= ' AND p.created_at <= "'.$new_date[2]."-".$new_date[1]."-".$new_date[0].'"';
}
//die($sql);
//Connect to your database
//CONFIGURA??ES DO BD MYSQL                               

//CONECTA COM O MYSQL
$mysqli = new mysqli($servidor, $usuario, $senha, $bd);
//$conn   =   mysql_connect($servidor, $usuario, $senha);
//$db     =   mysql_select_db($bd, $conn);      


require('fpdf.php');
$pdf= new FPDF("L","pt","A4");
$pdf->AddPage();
 
//linhas da tabela
$pdf->SetFont('arial','',12);

//if($qtd_estoque != NULL){
//$result=mysql_query('select * from produto WHERE quantidade_estoque > "'.$qtd_estoque.'" ORDER BY titulo',$conn);
//}else{
$result = $mysqli->query($sql);
//}


$pdf->SetFont('arial','B',18);
$pdf->Cell(0,5,utf8_decode("Relatório Geral de Clientes"),0,1,'C');
$pdf->Cell(10,35,$result->num_rows." clientes listados",0,1,'L');
$pdf->Cell(0,5,"","B",1,'C');
$pdf->Ln(50);
 
//cabe?alho da tabela 
$pdf->SetFont('arial','',14);
//$pdf->Cell(40,20,'#',1,0,"L");
//$pdf->Cell(400,20,'Nome',1,0,"L");
//$pdf->Cell(80,20,'CPF/CPNJ',1,0,"L");
//$pdf->Cell(80,20,'RG/IE',1,0,"L");
//$pdf->Cell(80,20,'a',1,1,"L");
//$pdf->Cell(100,20,'Page '.$pdf->PageNo().'/{nb}',0,1,'L');

while($row = $result->fetch_assoc()){
    $pdf->Cell(100,20,utf8_decode('Cód.: ').$row['id'],0,0,"L");
	if($row['tipo'] == "f"){
	    $pdf->Cell(400,20,utf8_decode('Nome completo: ').$row['nome'],0,0,"L");
	    $pdf->Cell(300,20,'CPF/CNPJ: '.$row['cpf'],0,0,"L");
	    $pdf->Ln();
	    $pdf->Cell(300,20,'RG/IE: '.$row['rg'],0,0,"L");
	}else{
	    $pdf->Cell(400,20,utf8_decode('Razão Social: '.$row['razao_social']),0,0,"L");
	    $pdf->Cell(300,20,'CPF/CNPJ: '.$row['cnpj'],0,0,"L");
	    $pdf->Ln();
	    $pdf->Cell(300,20,'RG/IE: '.$row['ie'],0,0,"L");	
	}
    
    $pdf->Cell(300,20,utf8_decode('Endereço: ').$row['endereco'],0,0,"L");
    $pdf->Ln();
    $pdf->Cell(300,20,utf8_decode('Cidade: ').$row['cidade_nome'],0,0,"L");
    $pdf->Cell(100,20,'Estado: '.$row['uf'],0,0,"L");
    $pdf->Cell(300,20,'Telefone: '.$row['telefone_1'],0,0,"L");
    $pdf->Ln();
    $pdf->Cell(0,5,"","B",1,'C');
    //$pdf->Cell(80,20,$row['rg'],1,0,"L");
    //$pdf->Cell(80,20,$row['estado_id'],1,1,"L");
    //$pdf->Cell(90,20,$row['rg'],1,1,"R");
}
mysqli_close($mysqli);
$pdf->Output();//"arquivo.pdf","D"
?>
