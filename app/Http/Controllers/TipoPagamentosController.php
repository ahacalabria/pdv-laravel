<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\TipoPagamento;
use Redirect;
use View;
use Auth;
use Validator;
use Session;
use Datatables;

class TipoPagamentosController extends Controller
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
        if (Auth::user()->level == 'vendedor')
          return view('errors.302');
        else{
        return view('pdv.tipopagamentos');
      }
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
      if (Auth::user()->level == 'root')
        return view('pdv.createtipopagamento');
      else
        return view('errors.302');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
      if (Auth::user()->level == 'root'){
        $tipopagamento = TipoPagamento::create(Input::all());
        Session::flash('message', 'Tipo pagamento cadastrado com sucesso!');
        return Redirect::to('tipopagamentos');
      }else
          return view('errors.302');
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
        $tipopagamento = TipoPagamento::orderBy('id')->get();
        $tipopagamento = json_encode($tipopagamento);
        return $tipopagamento;
      }else{
        $tipopagamento = TipoPagamento::where('id',$id)->get();
        $tipopagamento = json_encode($tipopagamento);
        return $tipopagamento;
      }
    }
    public function datatables(){
      $tipopagamento = TipoPagamento::orderBy('id');
      return Datatables::of($tipopagamento)->make(true);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
      if (Auth::user()->level == 'root'){
        $tipopagamento = TipoPagamento::find($id);
        return View::make('pdv.edittipopagamento')->with('tipopagamento', $tipopagamento);
      }else
          return view('errors.302');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
      if (Auth::user()->level == 'root'){
      // validate
        // read more on validation at http://laravel.com/docs/validation
        $rules = array(
            'tipo'       => 'required'
            // 'email'      => 'required|email',
            // 'nerd_level' => 'required|numeric'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('tipopagamento/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::all());
        } else {
            // store
            $tipopagamento = TipoPagamento::find($id);
            $tipopagamento->tipo = Input::get('tipo');
            // $nerd->email      = Input::get('email');
            // $nerd->nerd_level = Input::get('nerd_level');
            $tipopagamento->save();

            // redirect
            Session::flash('message', 'Tipo pagamento atualizado com sucesso!');
            return Redirect::to('tipopagamentos');
        }
      }else
          return view('errors.302');
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
        $tipopagamentos = TipoPagamento::find($id);
        $tipopagamentos->delete();

        // redirect
        Session::flash('message', 'Tipo pagamento deletado com sucesso!');
        return Redirect::to('tipopagamentos');
        // $msg = array("message" => "Tipo produto deletado com sucesso!");
        // return json_encode(msg);
    }
  }
