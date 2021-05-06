<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Imposto;
use App\Produto;
use Session;
use Redirect;
use Auth;
use Datatables;

class ImpostosController extends Controller
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
    if (Auth::user()->level == 'vendedor')
      return view('errors.302');
    else{
      $impostos = Imposto::orderBy('id', 'DESC')->get();
      return view('produtos.impostos',compact('impostos'));
    }
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    if (Auth::user()->level == 'gerente' || Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        return view('errors.302');
    else
      return view('produtos.createimposto');
  }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    if (Auth::user()->level == 'gerente' || Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        return view('errors.302');
    else{
      $validator = $this->validar();
      // process the login
      if ($validator->fails()) {
         return Redirect::to('impostos/create')
              ->withErrors($validator->errors())
              ->withInput();
      }else{
      $imposto = Imposto::create(Input::all());
      Session::flash('message', 'Imposto atualizado com sucesso!');
      return $this->index();
      }
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
      $impostos = Imposto::orderBy('updated_at','DESC')->get();
      $impostos = json_encode($impostos);
      return $impostos;
    }else{
      $impostos = Imposto::where('id',$id)->get();
      $impostos = json_encode($impostos);
      return $produtostipos;
    }
  }
  public function datatables(){
    $impostos = Imposto::orderBy('updated_at','DESC');
    return Datatables::of($impostos)->make(true);
  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    if (Auth::user()->level == 'gerente' || Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        return view('errors.302');
    else{
      $imposto = Imposto::find($id);
      return View::make('produtos.editimposto')->with('imposto', $imposto);
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
    if (Auth::user()->level == 'gerente' || Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        return view('errors.302');
    else{
    // validate
      $rules = array(
          'nome'       => 'required',
          'valor'       => 'required'
          // 'email'      => 'required|email',
          // 'nerd_level' => 'required|numeric'
      );
      $validator = Validator::make(Input::all(), $rules);

      // process the login
      if ($validator->fails()) {
          return Redirect::to('impostos/' . $id . '/edit')
              ->withErrors($validator);
              // ->withInput(Input::except('password'));
      } else {
          // store
          $imposto = Imposto::with(['produtos' => function ($q) {
        $q->orderBy('produto_id');
      }])->find($id);
          $imposto->nome = Input::get('nome');
          $old_value = $imposto->valor;
          $new_value = Input::get('valor');
          $maior_valor = $new_value;
          $menor_valor = $old_value;
          // if($maior_valor < $menor_valor){ //nao precisa disso pois o imposto do produto pode baixar
          //   $maior_valor = $menor_valor;
          //   $menor_valor = $new_value;
          // }
          //pegar todos os produtos que tem esse imposto e realizar o seguinte calculo
          //produto->preco += (produto->custo / (1-($maior_valor/100))) - (produto->custo / (1-($menor_valor/100)))
          $imposto->valor = Input::get('valor');
          // $nerd->email      = Input::get('email');
          // $nerd->nerd_level = Input::get('nerd_level');

          // save
          $imposto->save();
          $array = $imposto->produtos()->pluck('produto.id')->toArray();
          foreach ($array as $prod_id) {
            $prod_temp = Produto::findOrFail($prod_id);
            $prod_temp->preco += ($prod_temp->custo / (1-($maior_valor/100))) - ($prod_temp->custo / (1-($menor_valor/100)));
            $prod_temp->save();
          }

          // redirect
          Session::flash('message', 'Imposto atualizado e custos dos produtos também alterados com sucesso!');
          return Redirect::to('impostos');
      }
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
    if (Auth::user()->level == 'gerente' || Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        return view('errors.302');
    else{
      $imposto = Imposto::find($id);
      $imposto->delete();

      // redirect
      Session::flash('message', 'Imposto deletado com sucesso!');
      return Redirect::to('impostos');
    }
  }
    /**
   * Validating fields.
   *
   * @return Validator
   */
  public function validar(){
      $rules = array(
            'nome' => 'required',
            'valor' => 'required',
        );
      return Validator::make(Input::all(), $rules);
  }
}

