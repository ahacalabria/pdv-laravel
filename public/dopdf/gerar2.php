
<?php

if(isset($_GET['qtd_estoque'])){
	$qtd_estoque = $_GET['qtd_estoque'];
}else{
	$qtd_estoque = NULL;
}

if(isset($_GET['qtd_porcentagem'])){
	$qtd_porcentagem = $_GET['qtd_porcentagem'];
}else{
	$qtd_porcentagem = NULL;
}


require("config.php");
// PRIMEIRAMENTE: INSTALEI A CLASSE NA PASTA FPDF DENTRO DE MEU SITE.
define('FPDF_FONTPATH','./font/'); 

// INSTALA AS FONTES DO FPDF
require('./fpdf.php'); 

// INSTALA A CLASSE FPDF
class PDF extends FPDF {

// CRIA UMA EXTENS?O QUE SUBSTITUI AS FUN??ES DA CLASSE. 
// SOMENTE AS FUN??ES QUE EST?O DENTRO DESTE EXTENDS ? QUE SER?O SUBSTITUIDAS.


    function Header(){ //CABECALHO
        global $codigo; // EXEMPLO DE UMA VARIAVEL QUE TER? O MESMO VALOR EM QUALQUER ?REA DO PDF.
        $l=5; // DEFINI ESTA VARIAVEL PARA ALTURA DA LINHA
        $this->SetXY(10,10); // SetXY - DEFINE O X E O Y NA PAGINA
        $this->Rect(10,10,190,280); // CRIA UM RET?NGULO QUE COME?A NO X = 10, Y = 10 E 
                                    //TEM 190 DE LARGURA E 280 DE ALTURA. 
                                    //NESTE CASO, ? UMA BORDA DE P?GINA.

        $this->Image('./img.png',11,11,40); // INSERE UMA LOGOMARCA NO PONTO X = 11, Y = 11, E DE TAMANHO 40.
        $this->SetFont('Arial','B',8); // DEFINE A FONTE ARIAL, NEGRITO (B), DE TAMANHO 8

        $this->Cell(170,15,'',0,0,'L'); 
        // CRIA UMA CELULA COM OS SEGUINTES DADOS, RESPECTIVAMENTE: 
        // LARGURA = 170, 
        // ALTURA = 15, 
        // TEXTO = 'INSIRA SEU TEXTO AQUI'
        // BORDA = 0. SE = 1 TEM BORDA SE 'R' = RIGTH, 'L' = LEFT, 'T' = TOP, 'B' = BOTTOM
        // QUEBRAR LINHA NO FINAL = 0 = N?O
        // ALINHAMENTO = 'L' = LEFT

        $this->Cell(20,$l,utf8_decode('N° '.$codigo),1,0,'C',0); 
        // CRIA UMA CELULA DA MESMA FORMA ANTERIOR MAS COM ALTURA DEFINIDA PELA VARIAVEL $l E 
        // INSERINDO UMA VARIAVEL NO TEXTO.

        $this->Ln(); // QUEBRA DE LINHA

        $this->Cell(190,10,'',0,0,'L');
        $this->Ln();
        $l = 17;
        $this->SetFont('Arial','B',12);
        $this->SetXY(10,15);
        $this->Cell(190,$l,'GS DISTRIBUIDOR CL','B',1,'C');
        $l=5;
        $this->SetFont('Arial','B',10);
        // $this->Cell(20,$l,'Dados 1:',0,0,'L');
        // $this->Cell(100,$l,'','B',0,'L');
        // $this->Cell(35,$l,'',0,0,'L');
        $this->Cell(15,$l,'Data:',0,0,'L');
        $this->Cell(20,$l,date('d/m/Y'),'B',0,'L'); // INSIRO A DATA CORRENTE NA CELULA

        $this->Ln();
        // $this->Cell(20,$l,'Dados 2:',0,0,'L');
        // $this->Cell(100,$l,'','B',0,'L');
        $this->Ln();
        // $this->Cell(20,$l,'Dados 3:',0,0,'L');
        // $this->Cell(100,$l,'','B',0,'L');
        // $this->Cell(35,$l,'',0,0,'L');
        // $this->Cell(15,$l,'Dados 4:',0,0,'L');
        // $this->Cell(20,$l,'','B',0,'L');
         $this->Ln();

        //FINAL DO CABECALHO COM DADOS
        //ABAIXO ? CRIADO O TITULO DA TABELA DE DADOS

        $this->Cell(190,2,'',0,0,'C'); 
        //ESPA?AMENTO DO CABECALHO PARA A TABELA
        $this->Ln();

        $this->SetTextColor(255,255,255);
        $this->Cell(190,$l,utf8_decode('Relatório de Produtos'),1,0,'C',1);
        $this->Ln();

        //TITULO DA TABELA DE SERVI?OS
        $this->SetFillColor(232,232,232);
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','B',8);
        $this->Cell(15,$l,utf8_decode('Código'),1,0,'L',1);
        $this->Cell(140,$l,'Nome',1,0,'L',1);
        $this->Cell(15,$l,'UN.',1,0,'C',1);
        $this->Cell(20,$l,utf8_decode('Preço(R$)'),1,0,'C',1);
        //$this->Cell(12,$l,'Titulo 5',1,0,'C',1);
        //$this->Cell(40,$l,'Titulo 6',1,0,'C',1);
        //$this->Cell(15,$l,'Titulo 7',1,0,'C',1);
        $this->Ln();

    }

    function Footer(){ // CRIANDO UM RODAPE

        $this->SetXY(15,280);
        $this->Rect(10,270,190,20);
$this->SetFont('Arial','',7);
        $this->Cell(190,7,utf8_decode('Página '.$this->PageNo().' de {nb}'),0,0,'C');
        $this->Ln();
        
  
    }


}

//CONECTE SE AO BANCO DE DADOS SE PRECISAR 
//include("config.php"); // A MINHA CONEX?O FICOU EM CONFIG.PHP
//Connect to your database                                                                


//CONECTA COM O MYSQL
$mysqli = new mysqli($servidor, $usuario, $senha, $bd);
//$conn   =   mysql_connect($servidor, $usuario, $senha);
//$db     =   mysql_select_db($bd, $conn);  

$pdf=new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AddPage(); // ADICIONA UMA PAGINA
$pdf->AliasNbPages(); // SELECIONA O NUMERO TOTAL DE PAGINAS, USADO NO RODAPE
$pdf->SetFont('Arial','',8);
$y = 59; // AQUI EU COLOCO O Y INICIAL DOS DADOS 


if($qtd_estoque != NULL){
//echo $qtd_estoque;
$sql = 'select p.*, u.nome as sigla from produto as p, unidade u WHERE p.unidade_id = u.id AND quantidade_estoque > "'.$qtd_estoque.'" AND p.deleted_at is NULL AND p.desabilitar = 0 ORDER BY titulo';
}else{
$sql = 'select p.*, u.sigla as sigla from produto as p, unidade u WHERE p.unidade_id = u.id AND p.deleted_at is NULL AND p.desabilitar = 0 ORDER BY titulo';
}


$result = $mysqli->query($sql);
$l=4; // ALTURA DA LINHA
while($row = $result->fetch_assoc()) {
// ENQUANTO OS DADOS VAO PASSANDO, O FPDF VAI INSERINDO OS DADOS NA PAGINA

    $preco = $row['preco'];

    $dados1 = $row['codigo'].'-';
    $dados2 = $row['titulo']; // NESTE CASO, EU DECODIFIQUEI OS DADOS, POIS ? UM CAMPO DE     TEXTO.
    $dados3 = $row['quantidade_estoque'];
    //$dados4 = $qtd_porcentagem == NULL ? $preco : ($preco + ($preco*($qtd_porcentagem/100.0)));
    if($qtd_porcentagem == NULL){
	$valor_final = $preco;
    }else{
$valor = $preco;
  $percentual = $qtd_porcentagem / 100.0;
  $valor_final = $valor + ($percentual * $valor);
    }
   $dados4 = number_format($valor_final, 2);

    $dados5 = $row['custo'];
    $dados6 = $row['descricao'];
    $dados7 = $row['pessoa_id'];

    $l = 5 * contaLinhas($dados2,80); 
    // Eu criei a fun??o "contaLinhas" para contar quantas linhas um campo pode conter se tiver largura = 48


    if($y + $l >= 230){           // 230 ? O TAMANHO MAXIMO ANTES DO RODAPE

        $pdf->AddPage();   // SE ULTRAPASSADO, ? ADICIONADO UMA P?GINA
        $y=59;             // E O Y INICIAL ? RESETADO

    }

    //DADOS
    $pdf->Cell(15,6,$row['id'],1,0,"R");
    $pdf->Cell(140,6,$row['titulo'],1,0,"L");
    $pdf->Cell(15,6,$row['sigla'],1,0,"C");
    $preco = $qtd_porcentagem == NULL ? $preco : ($preco + ($preco*($qtd_porcentagem/100.0)));
    $pdf->Cell(20,6,number_format($preco, 2, ',', '.'),1,1,"R");
    $y += $l;

}

function contaLinhas($text, $maxwidth){ 
    $lines=0;
    if($text==''){
        $cont = 1;
    }else{
        $cont = strlen($text);
    }
    if($cont < $maxwidth){
        $lines = 1;
    }else{
        if($cont % $maxwidth > 0){
            $lines = ($cont / $maxwidth) + 1; 
        }else{
            $lines = ($cont / $maxwidth); 
        }
    } 
    $lines = $lines + substr_count(nl2br($text),'
');
    return $lines;
}
mysqli_close($mysqli);
$pdf->Output(); // IMPRIME O PDF NA TELA
Header('Pragma: public'); // ESTA FUN??O ? USADA PELO FPDF PARA PUBLICAR NO IE
?>
