<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Produto;
use App\ProdutoImposto;
use App\Pessoa;
use App\Unidade;
use App\Imposto;
use Auth;
use App\ParceladoPagar;
use App\ParceladoReceber;
use Session;
use Redirect;

class FinanceiroController extends Controller
{
  public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('vendedor', ['except', 'index', 'create', 'store', 'edit', 'update', 'destroy']);

        // return $next($request);
    }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index($tipo = '')
  {
    if (Auth::user()->level == 'vendedor'){
        return view('errors.302');
    }else{
      $this->lookForContaVencidas();
      if($tipo == 'contasapagar'){
        return view('financeiro.indexpagar');
      }else if($tipo == 'contasareceber'){
        return view('financeiro.indexreceber');
      }
      
    }
  }
  public function lookForContaVencidas(){
    $today = date('Y-m-d');
    $contas = ParceladoPagar::where('data_vencimento','<',$today)
                              ->where('status','pendente')
                              ->get();
    foreach ($contas as $conta) {
        $conta->status = 'vencida';
        $conta->save();
    }
    $contas = ParceladoReceber::where('data_vencimento','<',$today)
                                ->where('status','pendente')
                                ->get();
    foreach ($contas as $conta) {
        $conta->status = 'vencida';
        $conta->save();
    }
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
    //   $validator = $this->validar();
    //   // process the login
    //   if ($validator->fails()) {
    //      return Redirect::to('produtos/create')
    //           ->withErrors($validator->errors())
    //           ->withInput();
    //   }else{
    //   $produto = Produto::create(Input::all());
    //   $temp = Input::get('impostos_id');
    //   $impostos_id = Array();
    //   $impostos_id=explode(',',$temp);
    //   $produto->impostos()->sync($impostos_id);
    //   return Redirect::route('produtos.index');
    // }
  }
  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    // if($id === 'all') {
    //   $produtos = Produto::orderBy('id')->get();
    //   $produtos = json_encode($produtos);
    //   return $produtos;
    // }else{
    //   $produtos = Produto::where('id',$id)->get();
    //   $produtos = json_encode($produtos);
    //   return $produtos;
    // }
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
      //     Session::flash('message', 'Produto atualizado com sucesso!');
      //     return Redirect::to('produtos');
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
}
