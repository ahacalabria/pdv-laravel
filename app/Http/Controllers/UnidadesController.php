<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Unidade;
use Redirect;
use View;
use Validator;
use Auth;
use Session;
use Datatables;

class UnidadesController extends Controller
{
  public function __construct()
    {
        $this->middleware('auth');
    }
  public function index()
  {
    if (Auth::user()->level == 'vendedor')
      return view('errors.302');
    else{
      $unidades = Unidade::orderBy('id')->get();
      return view('produtos.unidades',compact('unidades'));
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
      return view('produtos.createunidade');
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
      $rules = array(
          'nome'       => 'required',
          'sigla'       => 'required'
          // 'email'      => 'required|email',
          // 'nerd_level' => 'required|numeric'
      );
      $validator = Validator::make(Input::all(), $rules);

      // process the login
      if ($validator->fails()) {
          return Redirect::to('unidades/create')
              ->withErrors($validator);
              // ->withInput(Input::except('password'));
      } else {

        $unidade = Unidade::create(Input::all());
        Session::flash('message', 'Unidade de medida cadastrada com sucesso!');
        return Redirect::to('unidades');
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
      $unidades = Unidade::orderBy('id','DESC')->get();
      $unidades = json_encode($unidades);
      return $unidades;
    }else{
      $unidades = Unidade::where('id',$id)->get();
      $unidades = json_encode($unidades);
      return $unidades;
    }
  }

  public function datatables(){
    $unidades = Unidade::orderBy('id','DESC');
    return Datatables::of($unidades)->make(true);
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
      $unidade = Unidade::find($id);
      return View::make('produtos.editunidade')->with('unidade', $unidade);
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
          'sigla'       => 'required'
          // 'email'      => 'required|email',
          // 'nerd_level' => 'required|numeric'
      );
      $validator = Validator::make(Input::all(), $rules);

      // process the login
      if ($validator->fails()) {
          return Redirect::to('unidades/' . $id . '/edit')
              ->withErrors($validator);
              // ->withInput(Input::except('password'));
      } else {
          // store
          $unidade = Unidade::find($id);
          $unidade->nome = Input::get('nome');
          $unidade->sigla = Input::get('sigla');
          // $nerd->email      = Input::get('email');
          // $nerd->nerd_level = Input::get('nerd_level');
          $unidade->save();

          // redirect
          Session::flash('message', 'Unidade de medida atualizada com sucesso!');
          return Redirect::to('unidades');
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
    // delete
      $unidade = Unidade::find($id);
      $unidade->delete();

      // redirect
      Session::flash('message', 'Unidade de medida deletada com sucesso!');
      return Redirect::to('unidades');
    }
  }
}
