<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use App\Venda;
use Redirect;
use View;
use Validator;
use Session;

setlocale(LC_ALL,'pt_BR.UTF8');
mb_internal_encoding('UTF8'); 
mb_regex_encoding('UTF8');

class PrinterController extends Controller{
  public function __construct()
    {
        $this->middleware('auth');
    }
  public function getPrinters()
  {
      $response  = $this->runCommand( 'lpstat -p' );
      $printers  = array();

      foreach( $response as $row )
      {
          preg_match( '/printer\s(.*)\is/', $row, $printer );
          preg_match( '/is\s(.*)\./', $row, $statusCode );

          if( end( $printer ) )
          {
              $printers[] = array( 'name' => end( $printer ), 'status' => end( $statusCode ) );
          }
      }

      return array( 'printers' => $printers );
  }


  private function submit( $filename, $printerName = false, $capabilities = array() )
  {
      if( $printerName )
      {
          // $command = 'lp -d ' . $printerName . ' ';
          // $command = 'NOTEPAD /P ';
          $server_name=exec('hostname');
          // print_r($_SERVER['SERVER_ADDR']);
          $IP_SERVER = "192.168.0.27";
          $command = 'lpr -S '.$IP_SERVER.' -P TESTE2 ';
      }
      else
      {
          $command = 'lpr ';
      }

      if( $capabilities )
      {
          foreach( $capabilities  as $cap )
          {
              $command .= '-o ' . $cap . ' ';
          }
      }

      if( $filename )
      {
          $command .= $filename;
      }

      $this->runCommand( $command );
  }


  protected function runCommand( $command )
  {
      exec( escapeshellcmd( $command ), $output );

      return $output;
  }

  public function imprimir($venda_id){
    //codes number_format($preco, 2, ',', '.')
    $valor_desconto = 0.0;
    $ESC = "\x1b";
    $GS = "\x1d";
    $NUL = "\x00";
    $RESET = chr(hexdec('1b')).'@';
    $BOLD = $ESC."E".chr(1);
    $NOTBOLD = $ESC."E".chr(0);
    $BLANK_LINE = $ESC."d".chr(1);
    $BLANK_LINE_4 = $ESC."d".chr(4);
    $TEXT_CENTER = chr(hexdec('1b')).'a'.chr(1);
    $TEXT_LEFT = chr(hexdec('1b')).'a'.chr(0);
    $BARCODE_EX = $GS."k".chr(4)."987654321".$NUL;
    $MODE_64 = chr(hexdec('1b')).'SI';
    $CUT = chr(hexdec('1b')).'m';
    $FONT = chr(hexdec('1b')).'t'.chr(12);

    $venda = Venda::with('vendedor')->with('conferente')->with('cliente')->with(['produtos' => function ($q) {
        $q->orderBy('venda_produto.id');
      }])->find($venda_id);
    $fdate = date('d/m/Y H:i', strtotime($venda->data_venda));

    $conferente = " ";
    if($venda->conferente != NULL) $conferente = $venda->conferente->nome;
    // $date = str_replace('-', '/', $var);
    $nome_cliente = $venda->cliente->razao_social;
    if(empty($nome_cliente)){
      $nome_cliente = $venda->cliente->nome;
    }
    $content = "            GS DISTRIBUIDOR CL \n               J. do Norte \n (88) 9 9604-2782 / (88) 9 9671-3214"."\n             Vendedor: ".$venda->vendedor->nome."\n             Cod. Venda: ".$venda->id."\n"."_______________________________________\nData/Hora: ".$fdate."\nCod. Cliente: ".$venda->cliente->id."\nNome Cliente: ".$nome_cliente."\n\nPRODUTOS\nITEM COD DESC QTD UN VALOR UNIT VALOR SUBTOTAL\n-----------------------------------------------------------------------------\n";
    $index = 0;
     foreach ($venda->produtos as $produto) {
       $index++;
       $qtd_prod = number_format($produto->pivot->quantidade, 2, ',', '.');
       $preco_prod = number_format($produto->pivot->preco, 2, ',', '.');
       $subtotal_prod = number_format($produto->pivot->subtotal, 2, ',', '.');
      //  $content .= $produto->id." ".$produto->titulo." ".$qtd_prod." x R$ ".$preco_prod." = R$ ".$subtotal_prod."\n";
       $content .= $this->montar_cupom($index,$produto->id,$produto->titulo,$qtd_prod,$produto->unidade->sigla,$preco_prod,$subtotal_prod)."\n\n-----------------------------------------------------------------------------\n\n";
     }
     if($venda->tipo_desconto == 'd'){
      $valor_desconto = $venda->valor_desconto;
     }elseif($venda->tipo_desconto == 'p'){
      $valor_desconto = ($venda->valor_total*$venda->valor_desconto)/100;
     }

    $content .= "\nTotal de Itens:.$index.\nTip. Pg.: ".$venda->tipo_pagamento->tipo." \nSub-total: R$ ".number_format($venda->valor_total, 2, ',', '.')."\nDesconto: R$ ".number_format($valor_desconto, 2, ',', '.')."\nV. Total: R$ ".number_format($venda->valor_liquido, 2, ',', '.')."\n_______________________________________\n"."\n           *Sem valor fiscal!"."\n\n\n\n"."              Conferente:\n\n\n\n_______________________________________\n                Volume:\n\n\n\n_______________________________________\n               Entrega: "."\n\n\n\n_______________________________________\n";
    $path = "C:\\wamp64\\www\\sistema-pdv-clever\\public\\notas\\".date('Y').'\\'.date('m').'\\'.date('d');
    File::makeDirectory($path, 0775, true, true);
    $milliseconds = round(microtime(true) * 1000);
    $filename = $path.'\\cupom_venda_'.$venda_id."_".$venda->status."_".$milliseconds.".txt";
    // if(!file_exists($filename) || ($venda->status == "aberta")){
      $content .= "\n".$TEXT_CENTER.md5($filename);
      $content = utf8_decode($content);
      File::put($filename,$content);
    // }
    return $this->submit($filename,"TESTE");
  }
  public function imprimira4($venda_id){
    //codes
    $valor_desconto = 0.0;
    $linha = "_______________________________________________________________";
    $venda = Venda::with('vendedor')->with('conferente')->with('cliente')->with(['produtos' => function ($q) {
        $q->orderBy('venda_produto.id');
      }])->find($venda_id);
    $fdate = date('d/m/Y H:i', strtotime($venda->data_venda));

    $conferente = " ";
    if($venda->conferente != NULL) $conferente = $venda->conferente->nome;
    // $date = str_replace('-', '/', $var);
    $content = "GS DISTRIBUIDOR CL\n J. do Norte \n (88) 9 9604-2782 / (88) 9 9671-3214 \nVendedor: ".$venda->vendedor->nome."\nConferente: ".$conferente."\nCod. Venda: ".$venda->id."\n".$linha."\nData/Hora: ".$fdate."\nCod. Cliente: ".$venda->cliente->id."\nNome Cliente: ".$venda->cliente->nome.$venda->cliente->razao_social."\n\nPRODUTOS\nCod. Desc. Qt. Val. Total\n";
     foreach ($venda->produtos as $produto) {
       $qtd_prod = number_format($produto->pivot->quantidade, 2, ',', '.');
       $preco_prod = number_format($produto->pivot->preco, 2, ',', '.');
       $subtotal_prod = number_format($produto->pivot->subtotal, 2, ',', '.');
       $content .= $produto->id." ".$produto->titulo." ".$qtd_prod." x R$ ".$preco_prod." = R$ ".$subtotal_prod."\n";
     }
     if($venda->tipo_desconto == 'd'){
      $valor_desconto = $venda->valor_desconto;
     }elseif($venda->tipo_desconto == 'p'){
      $valor_desconto = ($venda->valor_total*$venda->valor_desconto)/100;
     }

    $content .= "\nTip. Pg.: ".$venda->tipo_pagamento->tipo." \nSub-total: R$ ".number_format($venda->valor_total, 2, ',', '.')."\nDesconto: R$ ".number_format($valor_desconto, 2, ',', '.')."\nV. Total: R$ ".number_format($venda->valor_liquido, 2, ',', '.')."\n\n".$linha."\n\n*Sem valor fiscal!\n\n";
    $path = 'notasA4/'.date('Y').'/'.date('m').'/'.date('d');
    File::makeDirectory($path, 0775, true, true);
    $milliseconds = round(microtime(true) * 1000);
    $filename = $path.'/cupom_venda_'.$venda_id."_".$venda->status."_".$milliseconds.".txt";
    // if(!file_exists($filename) || ($venda->status == "aberta")){
      File::put($filename,$content);
    // }
    return $this->submit($filename,"HL2270DW");
  }
  function montar_cupom($index, $codigo_produto = 0, $descricao_produto = '', $quantidade = '', $unidade = '', $valor_unitario = '', $subtotal = 0){
    //$cupom = "##### 9DDDDDDDDDDDDDDDDDD9QQQQQXUU9VVVVVV9SSSSSS";
    $limiter = 27;
    $montando ='';
    $montando .= str_pad($index, 3, "0", STR_PAD_LEFT);
    $montando .=" ";
    $montando .= str_pad($codigo_produto, 6, "0", STR_PAD_LEFT);
    $montando .=" ";
    $montando .= str_pad(substr($descricao_produto, 0, $limiter), $limiter, " ");
    if(strlen($descricao_produto)>$limiter) $montando .= "\n".str_pad(substr($descricao_produto, $limiter, strlen($descricao_produto)-$limiter), strlen($descricao_produto), " ");
    $montando .="\n           ";
    $montando .= str_pad($quantidade, 5, " ", STR_PAD_LEFT);
    $montando .= str_pad($unidade, 2);
    $montando .=" x ";
    $montando .= str_pad($valor_unitario, 6, " ");
    $montando .=" = ";
    $montando .= str_pad($subtotal, 6, " ", STR_PAD_LEFT);
    return $montando;
  }
  // public function montar_cupom($codigo_produto = 0, $descricao_produto = '', $quantidade = '', $unidade = '', $valor_unitario = '', $subtotal = 0){
  //   //$cupom = "##### 9DDDDDDDDDDDDDDDDDD9QQQQQXUU9VVVVVV9SSSSSS";
  //   $montando ='';
  //   $montando .= str_pad($codigo_produto, 6, "0", STR_PAD_LEFT);
  //   $montando .=" ";
  //   $montando .= str_pad(substr($descricao_produto, 0, 20), 20, " ");
  //   $montando .=" ";
  //   $montando .= str_pad($quantidade, 5, " ", STR_PAD_LEFT);
  //   $montando .= str_pad($unidade, 2);
  //   $montando .="x";
  //   $montando .= str_pad($valor_unitario, 6, " ");
  //   $montando .=" ";
  //   $montando .= str_pad($subtotal, 6, " ", STR_PAD_LEFT);
  //   return $montando;
  // }
}
