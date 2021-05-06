<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use App\Pessoa;
use App\Cidade;
use App\Estado;
use App\Venda;
use App\Produto;
use App\Categoria;
use App\User;
use App\ParceladoReceber;
use App\ParceladoPagar;
use Carbon\Carbon;
use Auth;
use BrowserDetect;

use Barryvdh\DomPDF\Facade as PDF;

class PdfController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    if (Auth::user()->level == 'root' || Auth::user()->level == 'administrador' || Auth::user()->level == 'gerente')
      return view('pdf.relatorios');
    else
        return view('errors.302');
  }

  public function relatorio($tipo)
  {
    if (Auth::user()->level == 'root' || Auth::user()->level == 'administrador' || Auth::user()->level == 'gerente'){
      switch ($tipo) {
        case 'vendas':
        $vendedores = User::with('funcionario')->where('level','vendedor')->lists('name','pessoa_id');
        $clientes = Pessoa::where('tipo_cadastro','cliente')->selectRaw('CONCAT(nome, " ", razao_social) as nome_c, id')
        ->orderBy('id')->lists('nome_c', 'id');
        return view('pdf.venda',compact('vendedores', 'clientes'));
        break;
        case 'vendasresumido':
        $vendedores = User::with('funcionario')->where('level','vendedor')->lists('name','pessoa_id');
        $clientes = Pessoa::where('tipo_cadastro','cliente')->selectRaw('CONCAT(nome, " ", razao_social) as nome_c, id')
        ->orderBy('id')->lists('nome_c', 'id');

        return view('pdf.vendasresumido',compact('vendedores', 'clientes'));
        break;
        case 'clientes':
        $estados = Estado::orderBy('id')->get();
        return view('pdf.cliente',compact('estados'));
        break;
        case 'fornecedores':
          $estados = Estado::orderBy('id')->get();
          return view('pdf.fornecedor',compact('estados'));
          break;
        case 'funcionarios':
          $estados = Estado::orderBy('id')->get();
          return view('pdf.funcionario',compact('estados'));
        break;
        case 'balanco':
          return view('pdf.balanco',compact('estados'));
          break;
        case 'prodinter':
            $categorias = Categoria::orderBy('id')->get();
            return view('pdf.prodinter', compact('categorias'));
            break;
        case 'prodexter':
              return view('pdf.prodexter');
              break;
        case 'movimentacao':
              return view('pdf.movimentacao');
              break;
        case 'despesas':
              return view('pdf.despesas');
              break;

        }
    }else{
      return view('errors.302');
    }
  }

    public function clientes(Request $request)
    {
      $estado_id = $request->estado_id;
      $cidade_id = $request->cidade_id;

      $var = $request->data_inicio;
      $date = str_replace('/', '-', $var);
      $data_inicio = date('Y-m-d', strtotime($date));

      $var = $request->data_fim;
      $date = str_replace('/', '-', $var);
      $data_fim = date('Y-m-d', strtotime($date));
      $data = Pessoa::where('tipo_cadastro','cliente')->with('cidade')->with('estado')
      ->when($estado_id!="", function ($query) use ($estado_id) {
        return $query->where('estado_id', '=',$estado_id);
      })
      ->when($request->data_inicio!="", function ($query) use ($data_inicio) {
        return $query->where('created_at', '>=',$data_inicio);
      })
      ->when($request->data_fim!="", function ($query) use ($data_fim) {
        return $query->where('created_at', '<=',$data_fim);
      })
      ->when(($cidade_id!="null" && $cidade_id!=""), function ($query) use ($cidade_id) {
        return $query->where('cidade_id', '=',$cidade_id);
      })
      ->get();
      if(count($data)>0){
        $date = date('Y-m-d H:i:s');
        $pdf = PDF::loadView('pdf.clientes',compact('data', 'date', 'invoice'));
        if(BrowserDetect::isDesktop()){
          return $pdf->stream();
        }else{
          return $pdf->download('relatorio_clientes.pdf');
        }
      }else{
        return "<p><i>Não há informações para esta busca.</i></p>";
      }
    }

    public function produtos()
    {
      $data = Produto::with('pessoa')->with('impostos')->with('categorias')->get();
      if(count($data)>0){
        $date = date('Y-m-d H:i:s');
        $pdf = PDF::loadView('pdf.produtos',compact('data', 'date', 'invoice'));
        if(BrowserDetect::isDesktop()){
          return $pdf->stream();
        }else{
          return $pdf->download('relatorio_produtos.pdf');
        }
      }else{
        return "<p><i>Não há informações para esta busca.</i></p>";
      }
    }

    public function fornecedores(Request $request)
    {
      $estado_id = $request->estado_id;
      $cidade_id = $request->cidade_id;

      $var = $request->data_inicio;
      $date = str_replace('/', '-', $var);
      $data_inicio = date('Y-m-d', strtotime($date));

      $var = $request->data_fim;
      $date = str_replace('/', '-', $var);
      $data_fim = date('Y-m-d', strtotime($date));
      $data = Pessoa::where('tipo_cadastro','fornecedor')->with('cidade')->with('estado')
      ->when($estado_id!="", function ($query) use ($estado_id) {
        return $query->where('estado_id', $estado_id);
      })
      ->when($request->data_inicio!="", function ($query) use ($data_inicio) {
        return $query->where('created_at', '>=',$data_inicio);
      })
      ->when($request->data_fim!="", function ($query) use ($data_fim) {
        return $query->where('created_at', '<=',$data_fim);
      })
      ->when(($cidade_id!="null" && $cidade_id!=""), function ($query) use ($cidade_id) {
        return $query->where('cidade_id', $cidade_id);
      })
      ->get();
      if(count($data)>0){
        $date = date('Y-m-d H:i:s');
        $pdf = PDF::loadView('pdf.fornecedores',compact('data', 'date', 'invoice'));
        if(BrowserDetect::isDesktop()){
          return $pdf->stream();
        }else{
          return $pdf->download('relatorio_fornecedores.pdf');
        }
      }else{
        return "<p><i>Não há informações para esta busca.</i></p>";
      }
    }

    public function funcionarios(Request $request)
    {
      $estado_id = $request->estado_id;
      $cidade_id = $request->cidade_id;

      $var = $request->data_inicio;
      $date = str_replace('/', '-', $var);
      $data_inicio = date('Y-m-d', strtotime($date));

      $var = $request->data_fim;
      $date = str_replace('/', '-', $var);
      $data_fim = date('Y-m-d', strtotime($date));
      $data = Pessoa::where('tipo_cadastro','funcionario')->with('cidade')->with('estado')
      ->when($estado_id!="", function ($query) use ($estado_id) {
        return $query->where('estado_id', $estado_id);
      })
      ->when($request->data_inicio!="", function ($query) use ($data_inicio) {
        return $query->where('created_at', '>=',$data_inicio);
      })
      ->when($request->data_fim!="", function ($query) use ($data_fim) {
        return $query->where('created_at', '<=',$data_fim);
      })
      ->when(($cidade_id!="null" && $cidade_id!=""), function ($query) use ($cidade_id) {
        return $query->where('cidade_id', $cidade_id);
      })
      ->get();
      if(count($data)>0){
        $date = date('Y-m-d H:i:s');
        $pdf = PDF::loadView('pdf.funcionarios',compact('data', 'date', 'invoice'));
        if(BrowserDetect::isDesktop()){
          return $pdf->stream();
        }else{
          return $pdf->download('relatorio_funcionarios.pdf');
        }
      }else{
        return "<p><i>Não há informações para esta busca.</i></p>";
      }
    }

    public function fluxodecaixa(Request $request)
    {
      $date = date('Y-m-d H:i:s');
      $var = $request->data_inicio;
      $date_temp = str_replace('/', '-', $var);
      $data_inicio_0 = date('Y-m-d', strtotime($date_temp));
      // setlocale(LC_TIME, 'pt_BR.utf8');
      $data_inicio = Carbon::createFromFormat('Y-m-d', $data_inicio_0);
      $data_limite = Carbon::createFromFormat('Y-m-d', $data_inicio_0)->addMonths(12);
      // $teste = Carbon::now()->addMonths(12);

      $parcelado_receber_pagos = ParceladoReceber::select(
      DB::raw('sum(valor_pago) as soma'),
      DB::raw("DATE_FORMAT(data_vencimento,'%m/%Y') as meses")
      )->where('data_vencimento','>=',$data_inicio)->where('data_vencimento','<=',$data_limite)->where('status','pago')->groupBy('meses')->get();

      $parcelado_pagar_pagos = ParceladoPagar::select(
      DB::raw('sum(valor_pago) as soma'),
      DB::raw("DATE_FORMAT(data_vencimento,'%m/%Y') as meses")
      )->where('data_vencimento','>=',$data_inicio)->where('data_vencimento','<=',$data_limite)->where('status','pago')->groupBy('meses')->get();

      $parcelado_receber_pendentes = ParceladoReceber::select(
      DB::raw('sum(valor) as soma'),
      DB::raw("DATE_FORMAT(data_vencimento,'%m/%Y') as meses")
      )->where('data_vencimento','>=',$data_inicio)->where('data_vencimento','<=',$data_limite)->where('status','pendente')->groupBy('meses')->get();

      $parcelado_pagar_pendentes = ParceladoPagar::select(
      DB::raw('sum(valor) as soma'),
      DB::raw("DATE_FORMAT(data_vencimento,'%m/%Y') as meses")
      )->where('data_vencimento','>=',$data_inicio)->where('data_vencimento','<=',$data_limite)->where('status','pendente')->orderBy('data_vencimento')->groupBy('meses')->get();

      $parcelado_receber_vencidos = ParceladoReceber::select(
      DB::raw('sum(valor) as soma'),
      DB::raw("DATE_FORMAT(data_vencimento,'%m/%Y') as meses")
      )->where('data_vencimento','>=',$data_inicio)->where('data_vencimento','<=',$data_limite)->where('status','vencida')->groupBy('meses')->get();

      $parcelado_pagar_vencidos = ParceladoPagar::select(
      DB::raw('sum(valor) as soma'),
      DB::raw("DATE_FORMAT(data_vencimento,'%m/%Y') as meses")
      )->where('data_vencimento','>=',$data_inicio)->where('data_vencimento','<=',$data_limite)->where('status','vencida')->groupBy('meses')->get();

      $total_contas_a_pagar_vencidas = [];
      $total_contas_a_receber_vencidas = [];
      $total_contas_a_receber_pendentes = [];
      $total_contas_a_pagar_pendentes = [];
      $total_contas_a_receber_pagas = [];
      $total_contas_a_pagar_pagas = [];
      $nomemeses = array(
        '01' => 'JAN',
        '02' => 'FEV',
        '03' => 'MAR',
        '04' => 'ABR',
        '05' => 'MAI',
        '06' => 'JUN',
        '07' => 'JUL',
        '08' => 'AGO',
        '09' => 'SET',
        '10' => 'OUT',
        '11' => 'NOV',
        '12' => 'DEZ'
      );

      $meses = Array();
      foreach ($parcelado_pagar_vencidos as $value) {
        if (!in_array($value->meses, $meses))
          $meses[] = $value->meses;
      }
      foreach ($parcelado_pagar_vencidos as $value) {
        $total_contas_a_pagar_vencidas[$value->meses] = $value->soma;
      }
      foreach ($parcelado_receber_vencidos as $value) {
        if (!in_array($value->meses, $meses))
          $meses[] = $value->meses;
      }
      foreach ($parcelado_receber_vencidos as $value) {
        $total_contas_a_receber_vencidas[$value->meses] = $value->soma;
      }
      foreach ($parcelado_receber_pendentes as $value) {
        if (!in_array($value->meses, $meses))
          $meses[] = $value->meses;
      }
      foreach ($parcelado_receber_pendentes as $value) {
        $total_contas_a_receber_pendentes[$value->meses] = $value->soma;
      }
      foreach ($parcelado_pagar_pendentes as $value) {
        if (!in_array($value->meses, $meses))
          $meses[] = $value->meses;
      }
      foreach ($parcelado_pagar_pendentes as $value) {
        $total_contas_a_pagar_pendentes[$value->meses] = $value->soma;
      }
      foreach ($parcelado_receber_pagos as $value) {
        if (!in_array($value->meses, $meses))
          $meses[] = $value->meses;
      }
      foreach ($parcelado_receber_pagos as $value) {
        $total_contas_a_receber_pagas[$value->meses] = $value->soma;
      }
      foreach ($parcelado_pagar_pagos as $value) {
        if (!in_array($value->meses, $meses))
          $meses[] = $value->meses;
      }
      foreach ($parcelado_pagar_pagos as $value) {
        $total_contas_a_pagar_pagas[$value->meses] = $value->soma;
      }
      // dd($parcelado_receber_pendentes);
      $pdf = PDF::loadView('pdf.fluxodecaixa',compact('meses',
      'total_contas_a_pagar_vencidas',
      'total_contas_a_receber_vencidas',
      'total_contas_a_receber_pendentes',
      'total_contas_a_pagar_pendentes',
      'total_contas_a_receber_pagas',
      'total_contas_a_pagar_pagas',
      'nomemeses',
      'date'))->setPaper('a4', 'landscape');
      return $pdf->stream();

    }

    public function vendas(Request $request)
    {
      $status = $request->status;
      $vendedor_id = $request->vendedor_id;
      $cliente_id = $request->cliente_id;
      $com_nota = $request->com_nota;
      $id_venda = $request->id_venda;
      $var = $request->data_inicio;
      $date = str_replace('/', '-', $var);
      $data_inicio = date('Y-m-d', strtotime($date));
      $var = $request->data_fim;
      $date = str_replace('/', '-', $var);
      $data_fim = date('Y-m-d', strtotime($date));

      $data = Venda::with('cliente')->with('vendedor')->with(['produtos' => function ($q) {
        $q->orderBy('venda_produto.id');
      }])
      ->when($status!="", function ($query) use ($status) {
        return $query->where('status','=', $status);
      })
      ->when($request->data_inicio!="", function ($query) use ($data_inicio) {
        return $query->where('data_venda', '>=',$data_inicio);
      })
      ->when($request->data_fim!="", function ($query) use ($data_fim) {
        return $query->where('data_venda', '<=',$data_fim);
      })
      ->when($cliente_id!="", function ($query) use ($cliente_id) {
        return $query->where('pessoa_cliente_id','=', $cliente_id);
      })
      ->when($vendedor_id!="", function ($query) use ($vendedor_id) {
        return $query->where('pessoa_vendedor_id', '<=',$vendedor_id);
      })
      ->when($com_nota!="", function ($query) use ($com_nota) {
        return $query->where('com_nota','=',$com_nota);
      })
      ->when($id_venda!="", function ($query) use ($id_venda) {
        return $query->where('id','=',$id_venda);
      })
      ->get();

      if(count($data)>0){
        $date = date('Y-m-d H:i:s');
        $pdf = PDF::loadView('pdf.vendas',compact('data', 'date', 'invoice'));
        if(BrowserDetect::isDesktop()){
          return $pdf->stream();
        }else{
          return $pdf->download('relatorio_vendas.pdf');
        }
      }else{
        return "<p><i>Não há informações para esta busca.</i></p>";
      }
    }

    public function venda_cupom(Request $request)
    {

      $id_venda = $request->id_venda;

      $data = Venda::with('cliente')->with('vendedor')->with(['produtos' => function ($q) {
        $q->orderBy('venda_produto.id');
      }])
      ->when($id_venda!="", function ($query) use ($id_venda) {
        return $query->where('id','=',$id_venda);
      })
      ->get();

      if(count($data)>0){
        $date = date('Y-m-d H:i:s');
        $pdf = PDF::loadView('pdf.venda_cupom',compact('data', 'date', 'invoice'));
        if(BrowserDetect::isDesktop()){
          return $pdf->stream();
        }else{
          return $pdf->download('relatorio_vendas.pdf');
        }
      }else{
        return "<p><i>Não há informações para esta busca.</i></p>";
      }
    }

  }
