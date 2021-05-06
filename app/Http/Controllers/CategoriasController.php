<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Categoria;
use Session;
use Redirect;
use Auth;
use Datatables;

class CategoriasController extends Controller
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
      $categorias = Categoria::orderBy('id')->get();
      return view('categorias.index',compact('categorias'));
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
    else
      return view('categorias.create');
  }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        return view('errors.302');
    else{
      $categoria = Categoria::create(Input::all());
      Session::flash('message', 'Categoria cadastrada com sucesso!');
      return $this->index();
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
      $categorias = Categoria::orderBy('id')->get();
      $categorias = json_encode($categorias);
      return $categorias;
    }else{
      $categorias = Categoria::where('id',$id)->get();
      $categorias = json_encode($categorias);
      return $categorias;
    }
  }

  public function datatables(){
    $categorias = Categoria::orderBy('id');
    return Datatables::of($categorias)->make(true);
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
      $categoria = Categoria::find($id);
      return View::make('categorias.edit')->with('categoria', $categoria);
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
    if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        return view('errors.302');
    else{
      $rules = array(
                'nome' => 'required',
            );

      $validator = Validator::make(Input::all(), $rules);

      // process the login
      if ($validator->fails()) {
          return Redirect::to('categorias/' . $id . '/edit')
              ->withErrors($validator->errors())
              ->withInput();
      } else {
          // store
          $categoria = Categoria::find($id);
          $categoria->nome = Input::get('nome');
          $categoria->save();
          // redirect
          Session::flash('message', 'Categoria atualizada com sucesso!');
          return $this->index();
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
    if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        return view('errors.302');
    // else
  }
}
