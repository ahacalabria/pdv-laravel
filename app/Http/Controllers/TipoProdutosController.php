<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\TipoProduto;
use Redirect;
use View;
use Validator;
use Session;

class TipoProdutosController extends Controller
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
      // $tipoprodutos = TipoProduto::orderBy('id')->get();
      return view('produtos.tipos');
  }

  public function gettipoprodutos($id){

  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
      return view('produtos.createtipo');
  }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
      $tipoproduto = TipoProduto::create(Input::all());
      return view('produtos.tipos');
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
      $produtostipos = TipoProduto::orderBy('id')->get();
      $produtostipos = json_encode($produtostipos);
      return $produtostipos;
    }else{
      $produtostipos = TipoProduto::where('id',$id)->get();
      $produtostipos = json_encode($produtostipos);
      return $produtostipos;
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
      $tipoproduto = TipoProduto::find($id);
      return View::make('produtos.edittipo')->with('tipoproduto', $tipoproduto);
      // // get the nerd
      //   $nerd = Nerd::find($id);
      //
      //   // show the edit form and pass the nerd
      //   return View::make('nerds.edit')
      //       ->with('nerd', $nerd);
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
      // read more on validation at http://laravel.com/docs/validation
      $rules = array(
          'nome'       => 'required'
          // 'email'      => 'required|email',
          // 'nerd_level' => 'required|numeric'
      );
      $validator = Validator::make(Input::all(), $rules);

      // process the login
      if ($validator->fails()) {
          return Redirect::to('tipoprodutos/' . $id . '/edit')
              ->withErrors($validator);
              // ->withInput(Input::except('password'));
      } else {
          // store
          $tipoproduto = TipoProduto::find($id);
          $tipoproduto->nome = Input::get('nome');
          // $nerd->email      = Input::get('email');
          // $nerd->nerd_level = Input::get('nerd_level');
          $tipoproduto->save();

          // redirect
          Session::flash('message', 'Tipo produto atualizado com sucesso!');
          return Redirect::to('tipoprodutos');
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
      $tipoproduto = TipoProduto::find($id);
      $tipoproduto->delete();

      // redirect
      Session::flash('message', 'Successfully deleted the nerd!');
      return Redirect::to('tipoprodutos');
      // $msg = array("message" => "Tipo produto deletado com sucesso!");
      // return json_encode(msg);
  }
}
