<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use App\ParceladoPagar;
use App\ParceladoReceber;
use Response;
use \Carbon\Carbon;
use App\Venda;
use Auth;
use Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if((Auth::user() != NULL) && Auth::user()->level=="vendedor") return Redirect::to('vendas');
        $today = date('Y-m-d');
        $this->lookForContaVencidas();
        $aPagarVencidas = DB::table('parcelado_pagar')->where('data_vencimento','=',$today)
                                -> where('status','pendente')
                                -> sum('valor');

        $aReceberVencidas = DB::table('parcelado_receber') ->where('data_vencimento','=',$today)
                                -> where('status','pendente')
                                -> sum('valor');

        $totalReceberAtraso = DB::table('parcelado_receber') -> where('data_vencimento','<',$today)
                                -> where('status','vencida')
                                -> sum('valor');

        $totalPagarAtraso = DB::table('parcelado_pagar') ->where('data_vencimento','<',$today)
                                -> where('status','vencida')
                                -> sum('valor');

        return view('/home', compact('aPagarVencidas','aReceberVencidas','totalReceberAtraso','totalPagarAtraso'));
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

  public function getGraphs(){

    $data_limite = Carbon::now()->addMonths(6);
    $data_inicio = Carbon::now()->subMonths(6);

    $vendas = Venda::select(
    DB::raw('count(*) as total'),
    DB::raw("DATE_FORMAT(data_venda,'%m/%Y') as meses")
    )->where('data_venda','>=',$data_inicio)->where('data_venda','<=',$data_limite)->groupBy('meses')->get();

    $parcelado_receber_pagos = ParceladoReceber::select(
    DB::raw('sum(valor) as soma'),
    DB::raw("DATE_FORMAT(data_vencimento,'%m/%Y') as meses")
    )->where('data_vencimento','>=',$data_inicio)->where('data_vencimento','<=',$data_limite)->where('status','pago')->groupBy('meses')->get();

    $parcelado_pagar_pagos = ParceladoPagar::select(
    DB::raw('sum(valor) as soma'),
    DB::raw("DATE_FORMAT(data_vencimento,'%m/%Y') as meses")
    )->where('data_vencimento','>=',$data_inicio)->where('data_vencimento','<=',$data_limite)->where('status','pago')->groupBy('meses')->get();

    $fluxocaixameses = array();
    $vendasmeses = array();
    $vendasmeses = array();
    $vendastotal = array();
    foreach ($parcelado_receber_pagos as $prp) {
      $fluxocaixameses[] = $prp->meses;
    }
    foreach ($parcelado_pagar_pagos as $ppp) {
        if (!in_array($ppp->meses, $fluxocaixameses))
          $fluxocaixameses[] = $ppp->meses;
    }
    $fcin = array();
    foreach ($parcelado_receber_pagos as $prp) {
      $fcin[] = $prp->soma;
    }
    $fcout = array();
    foreach ($parcelado_pagar_pagos as $ppp) {
      $fcout[] = $ppp->soma;
    }

    foreach ($vendas as $v) {
      $vendasmeses[] = $v->meses;
      $vendastotal[] = $v->total;
    }

    if(count($fcin) > count($fcout)){
      $fcout = array_pad($fcout, count($fcin), "0.00");
    }else if(count($fcin) < count($fcout)){
      $fcint = array_pad($fcint, count($fcout), "0.00");
    }
    $ip = $_SERVER['SERVER_ADDR'];
    return Response::json(['data' => ['success' => true, 'fluxocaixameses' => $fluxocaixameses, 'fcin' => $fcin, 'fcout' => $fcout, 'vendasmeses' => $vendasmeses, 'vendastotal' => $vendastotal, 'ip' => $ip]])
    ->header('Content-Type', 'application/json');
  }
}
