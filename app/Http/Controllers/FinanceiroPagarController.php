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
use App\ParceladoReceber;
use Session;
use Redirect;
use File;
use Auth;

class FinanceiroPagarController extends Controller
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
    if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        return view('errors.302');
    else{
      $tipopagamentos = TipoPagamento::orderBy('tipo')->get();
      return View::make('financeiro.createpagamento')
                ->with('tipopagamentos',$tipopagamentos);
    }
  }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    // var_dump(Input::all());
    $finpg = FinanceiroPagar::create(Input::all());
    $parcelas = json_decode(Input::get('todasasparcelas'));
    $qtd_parcelas = Input::get('quantidade_parcelas');
    $status = 'pendente';
    if($parcelas === '{}') return Response::json(['data' => ['success' => false, 'msg' => 'Estão falando algumas informações sobre a conta!']])
      ->header('Content-Type', 'application/json');
    else{
      foreach ($parcelas as $parcela) {
        $parceladoPagar = new ParceladoPagar();
        $parceladoPagar->financeiro_pagar_id = $finpg->id;
        $parceladoPagar->numero = $parcela->numero;
        $parceladoPagar->data_vencimento = $parcela->data_vencimento;
        $parceladoPagar->status = $status;
        $parceladoPagar->valor_pago = 0;
        $parceladoPagar->valor = $parcela->valor;
        $parceladoPagar->save();
      }
    $finpg->quantidade_parcelas_pagas = 0;
    $finpg->save();
    return Response::json(['data' => ['success' => true, 'msg' => 'Pagamento salvo com sucesso!']])
    ->header('Content-Type', 'application/json');
    }
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
      $parceladopagar = FinanceiroPagar::with('financeiro_pagar')->orderBy('data_vencimento', 'asc')->get();
      $parceladopagar = json_encode($parceladopagar);
      return $parceladopagar;
    }else{
      $parceladopagar = FinanceiroPagar::with('financeiro_pagar')->where('financeiro_pagar_id',$id)->get();
      $parceladopagar = json_encode($parceladopagar);
      return $parceladopagar;
    }
  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        return view('errors.302');
    else{
      $financeiropagar = FinanceiroPagar::find($id);
      $parcelas = ParceladoPagar::where('financeiro_pagar_id',$id)->get();
      $tipopagamentos = TipoPagamento::orderBy('id')->get();
      return View::make('financeiro.editpagamento')
        ->with('financeiropagar', $financeiropagar)
        ->with('parcelas', $parcelas)
        ->with('tipopagamentos', $tipopagamentos);
      }
  }
  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    // var_dump($id);
    // return 'ok';
    // validate
      // $validator = $this->validar();
      //
      // // process the login
      // if ($validator->fails()) {
      //     return Redirect::to('produtos/' . $id . '/edit')
      //         ->withErrors($validator->errors())
      //         ->withInput();
      // } else {
      //     // store
      //     $produto = Produto::find($id);
      //     $produto->pessoa_id = Input::get('pessoa_id');
      //     $produto->codigo = Input::get('codigo');
      //     $produto->codigo_ncm = Input::get('codigo_ncm');
      //     $produto->titulo = Input::get('titulo');
      //     $produto->custo = Input::get('custo');
      //     $produto->preco = Input::get('preco');
      //     $produto->unidade_id = Input::get('unidade_id');
      //     $produto->quantidade_estoque = Input::get('quantidade_estoque');
      //     $produto->descricao = Input::get('descricao');
      //     $temp = Input::get('impostos_id');
      //     $impostos_id = Array();
      //     $impostos_id=explode(',',$temp);
      //     $produto->impostos()->sync($impostos_id);
      //     $produto->save();
      //     // redirect
          // Session::flash('message', 'Valor recebido com sucesso!');
          // return Redirect::to('financeiroreceber');
      // }
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
      // $rules = array(
      //       'pessoa_id' => 'required',
      //       'codigo' => 'required',
      //       'codigo_ncm' => 'required',
      //       'titulo' => 'required',
      //       'custo' => 'required',
      //       'preco' => 'required',
      //       'quantidade_estoque' => 'required',
      //       'descricao' => 'required',
      //       'unidade_id' => 'required',
      //       'impostos_id' => 'required'
      //   );
      // return Validator::make(Input::all(), $rules);
  }

  public function pagamentovenda($id){
    if (Auth::user()->level == 'vendedor')
        return view('errors.302');
    else{
      $venda = Venda::with('vendedor')->find($id);
      $clientes = Pessoa::where('tipo_cadastro','cliente')->get();
      $tipopagamentos = TipoPagamento::orderBy('tipo')->get();
      return View::make('financeiro.pagamentovenda')
                ->with('venda', $venda)
                ->with('clientes', $clientes)
                ->with('tipopagamentos',$tipopagamentos);
    }
  }
}
