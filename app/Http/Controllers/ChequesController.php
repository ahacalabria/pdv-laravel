<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Anexo;
use Session;
use Redirect;
use Auth;
use Datatables;

class ChequesController extends Controller
{
  public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if (Auth::user()->level == 'vendedor')
        return view('errors.302');
      else{
        return view('cheques.index');
      }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      if($id === 'all') {
      $cheques = Anexo::with('vendas')->has('vendas')->orderBy('id')->get();
      $cheques = json_encode($cheques);
      return $cheques;
      }else{
        $cheque = Anexo::find($id);
        $cheque = json_encode($cheque);
        return $cheque;
      }
    }
    public function datatables(){
      $cheques = Anexo::with('vendas')->has('vendas')->orderBy('id');
      return Datatables::of($cheques)->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
          return view('errors.302');
      else
        return view('cheques.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cheque = Anexo::findOrFail($id);
        $cheque->historico = $request->historico;
        $cheque->save();
        Session::flash('message', 'Cheque atualizado com sucesso!');
        return Redirect::to('cheques');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
