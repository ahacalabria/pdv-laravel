<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Categoria;
use App\Subcategoria;
use Session;
use Redirect;

class SubcategoriasController extends Controller
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
  public function index($id)
  {
      $categoria = Categoria::orderBy('id')->get();
      $subcategorias = Subcategoria::orderBy('id')->get();
      return view('categorias.indexsubcategorias',compact('subcategorias'));
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
      $categorias = Categoria::orderBy('id')->get();
      return View::make('categorias.createsubcategorias')
              ->with('categorias', $categorias);
    }
  }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
      $subcategoria = Subcategoria::create(Input::all());
      Session::flash('message','Subcategoria cadastrada com sucesso!');
      return Redirect::to('subcategorias/'.$subcategoria->categoria_id);
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
      $subcategorias = Subcategoria::with('categoria')->orderBy('categoria_id')->get();
      $subcategorias = json_encode($subcategorias);
      return $subcategorias;
    }else{
      $categoria = Categoria::find($id);
      $subcategorias = Subcategoria::where('categoria_id',$id)->orderBy('categoria_id')->get();
      return view('categorias.indexsubcategorias',compact('subcategorias','categoria'));
    }
  }
  public function getSubcategoriasByCategoriaId($id){
    $subcategorias = Subcategoria::with('categoria')->where('categoria_id',$id)->orderBy('id')->get();
    $subcategorias = json_encode($subcategorias);
    return $subcategorias;
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
        // show the edit form and pass the nerd
        $categorias = Categoria::orderBy('id')->get();
        $subcategoria = Subcategoria::find($id);
        return View::make('categorias.editsubcategorias')
                      ->with('subcategoria', $subcategoria)
                      ->with('categorias', $categorias);
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
      $rules = array(
                'categoria_id' => 'required',
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
          $subcategoria = Subcategoria::find($id);
          $subcategoria->nome = Input::get('nome');
          $subcategoria->categoria_id = Input::get('categoria_id');
          $subcategoria->save();
          // redirect
          Session::flash('message', 'Subcategoria atualizada com sucesso!');
          return Redirect::to('subcategorias/'.$subcategoria->categoria_id);
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
      //
  }
}
