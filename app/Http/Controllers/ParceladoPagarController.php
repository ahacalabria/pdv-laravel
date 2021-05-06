<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use Response;
use App\Venda;
use App\Produto;
use App\Pessoa;
use App\Anexo;
use App\TipoPagamento;
use App\FinanceiroPagar;
use App\ParceladoPagar;
use Session;
use Redirect;
use File;
use Datatables;

class ParceladoPagarController extends Controller
{
  public function __construct()
    {
        $this->middleware('auth');
    }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
      return view('financeiro.index');
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
      // return view('produtos.create');
  }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    // var_dump(Input::all());
    $finrec = FinanceiroReceber::create(Input::all());
    $finrec->valor_total = Input::get('valor_total');
    $quantidade_parcelas_pagas = 0;
    $venda_id = $finrec->venda_id;
    $venda = Venda::find($venda_id);
    $venda->status = Input::get('venda_status');
    $venda->tipo_pagamento_id = Input::get('tipo_pagamento_id');
    $venda->save();
    $pagamento_id = Input::get('tipo_pagamento_id');
    $anexo = Input::get('todasasparcelas');
    $qtd_parcelas = Input::get('quantidade_parcelas');
    $status = 'pendente';
    if($pagamento_id == 1){//a vista
      $status = 'pago';
      $quantidade_parcelas_pagas = 1;
    }else if($pagamento_id == 2){//cartao

    }else if($pagamento_id == 3){//cheque
      if($anexo === '{}') return Response::json(['data' => ['success' => false, 'msg' => 'Estão falando algumas informações sobre o cheque!']])
      ->header('Content-Type', 'application/json');
      else{
        $img_path=$this->uploadImages(Input::file('cheques'), Input::get('venda_id'), $qtd_parcelas);
        $anex = Array();
        $anex = $this->saveAnexo($anexo, $venda_id, $img_path);
        $venda->anexos()->sync($anex);
      }
    }else if($pagamento_id == 4){//parcelado

    }else if($pagamento_id == 5){//cheque parcelado
      if($anexo === '{}') return Response::json(['data' => ['success' => false, 'msg' => 'Estão falando algumas informações sobre algum cheque!']])
      ->header('Content-Type', 'application/json');
      else{
        $img_path=$this->uploadImages(Input::file('cheques'), Input::get('venda_id'), $qtd_parcelas);
        if($img_path == NULL){
          return Response::json(['data' => ['success' => false, 'msg' => 'Estão falando algumas informações sobre algum cheque!']])
          ->header('Content-Type', 'application/json');
        }else{
          $anex = Array();
          $anex = $this->saveAnexo($anexo, $venda_id, $img_path);
          $venda->anexos()->sync($anex);
        }
      }
    }

    if($pagamento_id == 1){
      $parcelasvencimento = Input::get('parcelasvencimento');
      $parcelasvalor = Input::get('parcelasvalor');
      $parcelasobs = Input::get('parcelasobs');
      $parceladoReceber = new ParceladoReceber();
      $parceladoReceber->financeiro_receber_id = $finrec->id;
      $parceladoReceber->data_pago = date('Y-m-d');
      $var = $parcelasvencimento[0];
      $date = str_replace('/', '-', $var);
      $parceladoReceber->data_vencimento = date('Y-m-d', strtotime($date));
      $parceladoReceber->obs = $parcelasobs[0];
      $parceladoReceber->status = $status;
      $parceladoReceber->valor_pago = Input::get('valor_recebido');
      $parceladoReceber->valor_troco = Input::get('valor_troco');
      $parceladoReceber->valor = $parcelasvalor[0];
      $parceladoReceber->save();
    }else{
      $parcelasvencimento = Input::get('parcelasvencimento');
      $parcelasvalor = Input::get('parcelasvalor');
      $parcelasobs = Input::get('parcelasobs');
      for ($i=0; $i<$qtd_parcelas; $i++) {
        $parceladoReceber = new ParceladoReceber();
        $parceladoReceber->financeiro_receber_id = $finrec->id;
        $var = $parcelasvencimento[$i];
        $date = str_replace('/', '-', $var);
        $parceladoReceber->data_vencimento = date('Y-m-d', strtotime($date));
        $parceladoReceber->data_pago = date('Y-m-d');
        $parceladoReceber->obs = $parcelasobs[$i];
        $parceladoReceber->status = $status;
        $parceladoReceber->valor_pago = Input::get('valor_recebido');
        $parceladoReceber->valor_troco = Input::get('valor_troco');
        $parceladoReceber->valor = $parcelasvalor[$i];
        $parceladoReceber->save();
      }
    }
    $finrec->quantidade_parcelas_pagas = $quantidade_parcelas_pagas;
    $finrec->save();
    return Response::json(['data' => ['success' => true, 'msg' => 'Informações salvas com sucesso!']])
    ->header('Content-Type', 'application/json');
  }

  private function saveAnexo($todasasparcelas, $venda_id, $img_path){
      $anexos = NULL;
      $todasasparcelas = json_decode($todasasparcelas);
      foreach ($todasasparcelas as $anexo) {
        $newanexo = new Anexo();
        $newanexo->nome = $anexo->nomeeminente;
        $newanexo->tipo_pessoa = $anexo->tipopessoa;
        $newanexo->historico = $anexo->historicocheque;
        $var = $anexo->dataemissao;
        $date = str_replace('/', '-', $var);
        $newanexo->data_emissao = date('Y-m-d', strtotime($date));
        $newanexo->agencia = $anexo->agencia;
        $newanexo->conta_corrente = $anexo->contacorrente;
        $newanexo->numero_cheque = $anexo->numerocheque;
        $newanexo->valor = $anexo->valor;
        $var = $anexo->datavencimento;
        $date = str_replace('/', '-', $var);
        $newanexo->data_vencimento = date('Y-m-d', strtotime($date));
        $newanexo->cpfcnpj = $anexo->cpfcnpj;
        $newanexo->caminho = $img_path[$anexo->id-1]."";
        $newanexo->banco_id = $anexo->banco_id;
        $newanexo->save();
        $anexos[] = $newanexo->id;
      }
      return $anexos;
  }

  private function uploadImages($cheques, $venda_id, $qtd_parcelas){
    $path = 'uploads/'.date('Y').'/'.date('m').'/'.date('d');
    File::makeDirectory($path, 0775, true, true);
    $upload_imgs = NULL;
    $i=0;
    if(count($cheques) == $qtd_parcelas){
      foreach ($cheques as $image) {
        $imagename = 'venda_'.$venda_id.'_parcela_'.($i+1).'_de_'.$qtd_parcelas.'.'.$image->getClientOriginalExtension();
        $up_flag = $image->move($path, $imagename);
        if($up_flag){
          $upload_imgs[] = $path.'/'.$imagename;
          $i++;
        }
      }
    }
      return $upload_imgs;
  }
  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    if($id === 'all') {
      $parceladopagar = ParceladoPagar::with('financeiro_pagar')->orderBy('data_vencimento', 'asc')->get();
      $parceladopagar = json_encode($parceladopagar);
      return $parceladopagar;
    }else{
      $parceladopagar = ParceladoPagar::with('financeiro_pagar')->where('financeiro_pagar_id',$id)->get();
      $parceladopagar = json_encode($parceladopagar);
      return $parceladopagar;
    }
  }
  public function datatables(){
    $parceladopagar = ParceladoPagar::with('financeiro_pagar')->orderBy('data_vencimento', 'asc');
    return Datatables::of($parceladopagar)->make(true);
  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    // $produto = Produto::find($id);
    // $fornecedores = Pessoa::where('tipo_cadastro','fornecedor')->orderBy('id')->get();
    // $unidades = Unidade::orderBy('id')->get();
    // return View::make('produtos.edit')
    //   ->with('produto', $produto)
    //   ->with('fornecedores', $fornecedores)
    //   ->with('unidades', $unidades);
  }
  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    // validate
      $validator = $this->validar();
      $par_pg = ParceladoPagar::find($id);

      // process the login
      if ($validator->fails()) {
        Session::flash('message', 'A validação falhou!');
        return view('financeiro.indexpagar');
      } if($par_pg->valor > Input::get('valor_pago') ){
        Session::flash('message', 'QUASE LÁ! Valor menor do que o valor da conta, tente novamente!');
        return view('financeiro.indexpagar');
      }else {
          // store
          // $par_pg->valor_troco = Input::get('unidade_id');
          $par_pg->valor_pago = Input::get('valor_pago');
          $par_pg->status = 'pago';
          $par_pg->data_pago = date('Y-m-d');
          $par_pg->save();
          $par_pg->financeiro_pagar->quantidade_parcelas_pagas=$par_pg->financeiro_pagar->quantidade_parcelas_pagas+1;
          $par_pg->financeiro_pagar->save();
          // redirect
          Session::flash('message', 'Valor pago com sucesso!');
          return view('financeiro.indexpagar');
      }
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    // delete
      // $produto = Produto::find($id);
      // $produto->delete();
      //
      // // redirect
      // Session::flash('message', 'Successfully deleted the nerd!');
      // return Redirect::to('impostos');
  }
  /**
   * Validating fields.
   *
   * @return Validator
   */
  public function validar(){
      $rules = array(
            'valor_pago' => 'required',
            // 'valor_desconto' => 'required',
            // 'valor_juros' => 'required'
        );
      return Validator::make(Input::all(), $rules);
  }

  public function pagamentovenda($id){
    $venda = Venda::with('vendedor')->find($id);
    $clientes = Pessoa::where('tipo_cadastro','cliente')->get();
    $tipopagamentos = TipoPagamento::orderBy('tipo')->get();
    return View::make('financeiro.pagamentovenda')
              ->with('venda', $venda)
              ->with('clientes', $clientes)
              ->with('tipopagamentos',$tipopagamentos);
  }
}
