<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\Venda;
use App\Produto;
use App\Pessoa;
use App\TipoPagamento;
use App\FinanceiroReceber;
use App\ParceladoReceber;
use App\Movimentacao;
use Redirect;
use Response;
use View;
use Auth;
use Validator;
use Session;
use DateTime;
use App\Printer;
use Datatables;

class VendasController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    // $this->middleware('caixa');
    // $this->middleware('vendedor', ['only' => 'index']);
    // $this->middleware('log', ['only' => ['fooAction', 'barAction']]);

    // $this->middleware('vendedor', ['only' => ['index', 'show']]);
    // $this->middleware('vendedor', ['except' => ['VendasController@index', 'VendasController@show']]);
  }
  public function index()
  {
    return view('pdv.vendas');
  }
  public function etiquetas()
  {
    return view('pdv.etiquetas');
  }
  public function geraretiqueta()
  {
    if (Auth::user()->level == 'vendedor')
    return view('errors.302');
    else{
      return view('pdv.etiquetas');
    }
  }
  public function varejo()
  {
    return view('pdv.create_varejo');
  }
  /**
  * Show the form for creating a new resource.
  *
  * @return Response
  */
  public function create()
  {
    return view('pdv.create');
  }

  /**
  * Store a newly created resource in storage.
  *
  * @return Response
  */
  public function store()
  {
    // validate
    $validator = $this->validar();
    if ($validator->fails()) {
      return Response::json(['data' => ['success' => false, 'msg' => 'Estão faltando algumas informações sobre a venda! Por favor verificar.']])
      ->header('Content-Type', 'application/json');
    } else if( Input::get('tipo_desconto') == 'p' ){
      if(Input::get('valor_desconto') > Auth::user()->limite_porcetagem ){
        return Response::json(['data' => ['success' => false, 'msg' => 'ERRO DESCONTO: Limite em porcetagem acima do permitido!']])
        ->header('Content-Type', 'application/json');
      }
    }else if(Input::get('tipo_desconto') == 'd' ){
      if(Input::get('valor_desconto') > Auth::user()->limite_dinheiro){
        return Response::json(['data' => ['success' => false, 'msg' => 'ERRO DESCONTO: Limite em dinheiro acima do permitido!']])
        ->header('Content-Type', 'application/json');
      }
    }
    $venda = Venda::create(Input::all());
    if(Input::get('pessoa_conferente_id')=="")
      $venda->pessoa_conferente_id = $this->emptyConferente();
    else
      $venda->pessoa_conferente_id=Input::get('pessoa_conferente_id');
    $var = Input::get('data_venda');
    $date = str_replace('/', '-', $var);
    $venda->data_venda = date('Y-m-d H:i', strtotime($date));
    $venda->save();
    $temp = Input::get('produtos_id');
    $produtos_id = Array();
    $produtos_id=explode(',',$temp);
    $temp = Input::get('quantidades');
    $quantidades = Array();
    $quantidades=explode(',',$temp);
    $temp2 = Input::get('precos');
    $precos = Array();
    $precos=explode(',',$temp2);
    $data = Array();
    $check_estoque_disponivel = NULL;
    $qtd_disponivel=0;
    // dd($quantidades);
    foreach ($produtos_id as $key => $produto_id ) {
      $produto = Produto::with('unidade')->find($produto_id);
      if($produto->quantidade_estoque < $quantidades[$key]){
        $check_estoque_disponivel = $produto->titulo;
        $qtd_disponivel=$produto->quantidade_estoque;
        break;
      }
    }
    //if($check_estoque_disponivel == NULL){
    foreach ($produtos_id as $key => $produto_id ) {
      $produto = Produto::with('unidade')->find($produto_id);
      $produto->quantidade_estoque = ($produto->quantidade_estoque) - ($quantidades[$key]);

      $movimentacao = Movimentacao::create([
        'numero_nota' => $venda->id,
        'emitente_destinatario' => 'Saída Venda',
        'valor_unitario' => $precos[$key],
        'valor_total' => ($quantidades[$key])*($precos[$key]),
        'quantidade' => -$quantidades[$key],
        'estoque' => $produto->quantidade_estoque,
      ]);
      $produto->movimentacoes()->sync([$movimentacao->id],false);

      $subtotal = ($quantidades[$key]) * ($precos[$key]);
      $data[] = [ 'venda_id' => $venda->id , 'produto_id' =>  $produto_id, 'quantidade' => $quantidades[$key],
      'codigo' => $produto->codigo, 'codigo_ncm' => $produto->codigo_ncm, 'titulo' => $produto->titulo,
      'descricao' => $produto->descricao, 'custo' => $produto->custo, 'preco' => $precos[$key],
      'unidade_nome' => $produto->unidade->nome, 'subtotal' => $subtotal, 'impostos_info' => json_encode($produto->impostos)];
      $produto->save();
    }
    $venda->produtos()->sync($data);
    return Response::json(['data' => ['success' => true, 'msg' => 'Venda salva com sucesso!', 'id' => $venda->id]])
    ->header('Content-Type', 'application/json');
    // }else{
    //   return Response::json(['data' => ['success' => false, 'msg' => $check_estoque_disponivel.': quantidade em estoque '.$qtd_disponivel]])
    //   ->header('Content-Type', 'application/json');
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
    if($id === 'all') {
      if(Auth::user()->level == "vendedor"){
        $vendas = Venda::with('cliente')->with('conferente')->with('vendedor')
        ->where('pessoa_vendedor_id','=',Auth::user()->funcionario->id)
        ->orderBy('id','Desc')->get();
      }else{
        $vendas = Venda::with('cliente')->with('conferente')->with('vendedor')->orderBy('id','Desc')->get();          
      }
      $vendas = json_encode($vendas);
      return $vendas;
      // return Datatables::of($vendas)->make(true);
    }else{
      $vendas = Venda::with('vendedor')->with('conferente')->with('cliente')
      ->with(['produtos' => function ($q) {
        $q->orderBy('venda_produto.id');
      }])->find($id);
      $vendas = json_encode($vendas);
      return $vendas;
    }
  }

  public function datatables(){
    if(Auth::user()->level == "vendedor"){
      $vendas = Venda::with('cliente')->with('conferente')->with('vendedor')
      ->where('pessoa_vendedor_id','=',Auth::user()->funcionario->id)
      ->orderBy('id','Desc');
    }else{
      $vendas = Venda::with('cliente')->with('conferente')->with('vendedor')->orderBy('id','Desc');
    }
    // \Log::info($vendas->toSql());
    return Datatables::of($vendas)->make(true);
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
  public function edit($id)
  {
    $venda = Venda::with('vendedor')->with(['produtos' => function ($q) {
      $q->orderBy('venda_produto.id');
    }])->find($id);
    $clientes = Pessoa::where('tipo_cadastro','cliente')->get();
    $funcionarios = Pessoa::where('tipo_cadastro','funcionario')->get();
    $tipopagamentos = TipoPagamento::orderBy('tipo')->get();
    return View::make('pdv.edit')
    ->with('venda', $venda)
    ->with('clientes', $clientes)
    ->with('funcionarios', $funcionarios)
    ->with('tipopagamentos',$tipopagamentos);
  }

  public function corrigirvendasavista()
  {
    $vendas = Venda::with('tipo_pagamento')->
    whereHas('tipo_pagamento', function ($q) {
        $q->where('id', '=', 1);
      })->pluck('id');
    $fin_rec = FinanceiroReceber::whereIn('venda_id',$vendas)->pluck('id');

    $parc_rec = ParceladoReceber::whereIn('financeiro_receber_id',$fin_rec)->get();

    foreach($parc_rec as $pg_avista){
      $pg_avista->data_vencimento = $pg_avista->data_pago;
      $pg_avista->save();
    }

    return json_encode($parc_rec);
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  int  $id
  * @return Response
  */
  public function update($id)
  {
    // validate request fields
    $validator = $this->validar();
    if ($validator->fails()) {
      // return Response::json(['data' => ['success' => false, 'msg' => 'Estão faltando algumas informações sobre a venda! Por favor verificar.']])
      // ->header('Content-Type', 'application/json');
      $msg = "ERRO: Estão faltando algumas informações sobre a venda! Por favor verificar.";
      Session::flash('message', $msg);
      return Redirect::back();
    } else if( Input::get('tipo_desconto') == 'p' ){
      if(Input::get('valor_desconto') > Auth::user()->limite_porcetagem ){
        // return Response::json(['data' => ['success' => false, 'msg' => 'ERRO DESCONTO: Limite em porcetagem acima do permitido!']])
        // ->header('Content-Type', 'application/json');
        $msg = "ERRO DESCONTO: Limite em porcetagem acima do permitido!";
        Session::flash('message', $msg);
        return Redirect::back();
      }
    }else if(Input::get('tipo_desconto') == 'd' ){
      if(Input::get('valor_desconto') > Auth::user()->limite_dinheiro){
        // return Response::json(['data' => ['success' => false, 'msg' => 'ERRO DESCONTO: Limite em dinheiro acima do permitido!']])
        // ->header('Content-Type', 'application/json');
        $msg = "ERRO DESCONTO: Limite em dinheiro acima do permitido!";
        Session::flash('message', $msg);
        return Redirect::back();
      }
    }
    // update
    $venda = Venda::with(['produtos' => function ($q) {
      $q->orderBy('venda_produto.id');
    }])->find($id);
    // pega todos os produtos atuais da venda antes de edita-los
    // echo "1\n";
    // var_dump($venda);
    // echo "2\n";
    // var_dump($venda->produtos()->getRelatedIds());

    $temp = Input::get('produtos_id');
    $produtos_id = Array();
    $produtos_id=explode(',',$temp);
    // echo "3\n";
    // var_dump($temp); //imprimindo temp 1 vez
    $temp = Input::get('quantidades');
    $quantidades = Array();
    $quantidades=explode(',',$temp);
    $data = Array();
    $check_estoque_disponivel = NULL;
    $qtd_disponivel=0;
    // echo "4\n";
    // var_dump($temp); //imprimindo temp 2 vez
    // exit(1);
    foreach ($produtos_id as $key => $produto_id ) {
      $array_temp = $venda->produtos()->orderBy('venda_produto.id')->select('produto.id')->lists('id')->all();
      // var_dump($array_temp);
      if(in_array($produto_id, $array_temp)){

        $produto = Produto::with('unidade')->find($produto_id);
        $produto_ant = $venda->produtos->find($produto->id);
        if($produto_ant->pivot->quantidade > $quantidades[$key]){
          $produto->quantidade_estoque = $produto->quantidade_estoque + ($produto_ant->pivot->quantidade - $quantidades[$key]);
          $produto->save();
        }else if($produto_ant->pivot->quantidade < $quantidades[$key]){
          // $qtd_solicitada_extra =  ($quantidades[$key] - $produto_ant->pivot->quantidade);
          // if($produto->quantidade_estoque < $qtd_solicitada_extra){
          //   $check_estoque_disponivel = $produto->titulo;
          //   $qtd_disponivel=$produto->quantidade_estoque;
          //   break;
          // }else{
          $produto->quantidade_estoque = $produto->quantidade_estoque - ($quantidades[$key] - $produto_ant->pivot->quantidade);
          $produto->save();
          // }
        }
      }else{
        foreach ($venda->produtos as $produto) {
          if($produto_id == $produto->id){
            $produto_edited = Produto::find($produto->id);
            $produto_edited->quantidade_estoque = $produto_edited->quantidade_estoque + $produto->pivot->quantidade;
            $produto_edited->save();
            break;
          }
        }
      }
    }
    // if($check_estoque_disponivel == NULL){

    $venda->tipo_pagamento_id=Input::get('tipo_pagamento_id');
    $venda->pessoa_cliente_id=Input::get('pessoa_cliente_id');
    $venda->pessoa_conferente_id=Input::get('pessoa_conferente_id');
    $venda->pessoa_vendedor_id=Input::get('pessoa_vendedor_id');
    $venda->status=Input::get('status');
    $venda->valor_total=Input::get('valor_total');
    $venda->tipo_desconto=Input::get('tipo_desconto');
    $venda->valor_desconto=Input::get('valor_desconto');
    $venda->valor_frete=Input::get('valor_frete');
    $venda->valor_liquido=Input::get('valor_liquido');
    $var = Input::get('data_venda');
    $date = str_replace('/', '-', $var);
    $venda->data_venda = date('Y-m-d H:i', strtotime($date));
    $temp = Input::get('produtos_id');
    $produtos_id = Array();
    $produtos_id=explode(',',$temp);
    $temp = Input::get('quantidades');
    $quantidades = Array();
    $quantidades=explode(',',$temp);
    $temp2 = Input::get('precos');
    $precos = Array();
    $precos=explode(',',$temp2);
    $data = Array();

    foreach ($produtos_id as $key => $produto_id ) {
      $produto = Produto::with('unidade')->find($produto_id);
      // $produto->quantidade_estoque = ($produto->quantidade_estoque) - ($quantidades[$key]);

      $movimentacao = Movimentacao::create([
        'numero_nota' => $venda->id,
        'emitente_destinatario' => 'Editando Venda',
        'valor_unitario' => $precos[$key],
        'valor_total' => $precos[$key] * $quantidades[$key],
        'quantidade' => -$quantidades[$key],
        'estoque' => $produto->quantidade_estoque,
      ]);
      $produto->movimentacoes()->sync([$movimentacao->id],false);

      $subtotal = ($quantidades[$key]) * ($precos[$key]);
      $data[] = [ 'venda_id' => $venda->id , 'produto_id' =>  $produto_id, 'quantidade' => $quantidades[$key],
      'codigo' => $produto->codigo, 'codigo_ncm' => $produto->codigo_ncm, 'titulo' => $produto->titulo,
      'descricao' => $produto->descricao, 'custo' => $produto->custo, 'preco' => $precos[$key],
      'unidade_nome' => $produto->unidade->nome, 'subtotal' => $subtotal, 'impostos_info' => json_encode($produto->impostos)];
      // $produto->save();
    }
    // var_dump($data);
    $venda->produtos()->detach();
    $venda->produtos()->sync($data);
    $venda->save();
    // return Response::json(['data' => ['success' => true, 'msg' => 'Venda salva com sucesso!']])
    // ->header('Content-Type', 'application/json');
    // redirect
    // $msg = "Venda #".$id." atualizada com sucesso!";
    // Session::flash('message', $msg);
    // return Redirect::to('vendas');
    return Response::json(['data' => ['success' => true, 'msg' => 'Venda atualizada com sucesso!']])
    ->header('Content-Type', 'application/json');
    // }else{
    //   // return Response::json(['data' => ['success' => false, 'msg' => $check_estoque_disponivel.': quantidade em estoque '.$qtd_disponivel]])
    //   // ->header('Content-Type', 'application/json');
    //   $msg = "Venda #".$id."- ERRO - ".$check_estoque_disponivel.": quantidade em estoque ".$qtd_disponivel;
    //   Session::flash('message', $msg);
    //   return Redirect::back();
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
    if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
      return view('errors.302');
    else{
      // delete
      $venda = Venda::with(['produtos' => function ($q) {
        $q->orderBy('venda_produto.id');
      }])->find($id);
      if($venda->status != "cancelada"){
        $venda->status = "cancelada";
        $venda->save();
        foreach ($venda->produtos as $produto) {
          $produto_edited = Produto::find($produto->id);
          $produto_edited->quantidade_estoque = $produto_edited->quantidade_estoque + $produto->pivot->quantidade;

          $movimentacao = Movimentacao::create([
            'numero_nota' => $venda->id,
            'emitente_destinatario' => 'Estorno Venda',
            'valor_unitario' => $produto->pivot->preco,
            'valor_total' => $produto->pivot->preco * $produto->pivot->quantidade,
            'quantidade' => $produto->pivot->quantidade,
            'estoque' => $produto_edited->quantidade_estoque,
          ]);
          $produto->movimentacoes()->sync([$movimentacao->id],false);

          $produto_edited->save();
        }

        $fin_rec = FinanceiroReceber::where('venda_id',$id)->get();
        //dd($fin_rec);
        if(count($fin_rec) > 0 ){
            $parcelas_rec = ParceladoReceber::where('financeiro_receber_id',$fin_rec[0]->id)->get();
            foreach($parcelas_rec as $parc){
              $parc->delete();
            }
    
            $fin_rec[0]->delete();
        }
        // redirect
        Session::flash('message', 'Venda cancelada com sucesso!!!');
        return Redirect::back();
      }else{
        // redirect
        Session::flash('message', 'Venda já cancelada!');
        return Redirect::back();
      }
    }
  }
  /**
  * Validating fields.
  *
  * @return Validator
  */
  public function validar(){
    $rules = array(
      'tipo_pagamento_id' => 'required',
      'pessoa_cliente_id' => 'required',
      'pessoa_vendedor_id' => 'required',
      // 'pessoa_conferente_id' => 'required',
      'status' => 'required',
      'valor_total' => 'required',
      'tipo_desconto' => 'required',
      'valor_desconto' => 'required',
      'valor_frete' => 'required',
      'valor_liquido' => 'required',
      'data_venda' => 'required',
      'produtos_id' => 'required',
      'quantidades' => 'required'
    );
    return Validator::make(Input::all(), $rules);
  }


  // 'tipo_cadastro','tipo', 'nome', 'sobrenome','nome_fantasia', 'razao_social',
  // 'cpf', 'cnpj', 'rg', 'ie', 'data_nascimento', 'estado_id', 'cidade_id', 'endereco', 'bairro',
  // 'cep', 'telefone_1', 'telefone_2', 'email', 'site', 'nome_responsavel', 'telefone_responsavel',
  // 'banco_id', 'agencia', 'conta', 'observacao', 'sexo', 'cargo'

  public function emptyConferente(){
    $nome = "(sem conferente)";
    $verf = Pessoa::where('nome','=',$nome)->where('tipo_cadastro','=','funcionario')->first();
    if($verf) return $verf->id;
    else{
    $conferente = Pessoa::firstOrCreate([
      'tipo_cadastro'=>'funcionario',
      'tipo' => 'f',
      'nome' => '(sem conferente)',
      'sobrenome' => '',
      'nome_fantasia' => '',
      'razao_social' => '',
      'cpf' => '12332112312',
      'cnpj' => '',
      'rg' => '',
      'ie' => '',
      'data_nascimento' => '',
      'estado_id' => '1',
      'cidade_id' => '1',
      'endereco' => 'rua abc',
      'bairro' => 'bairo XYZ',
      'cep' => '1234565',
      'telefone_1' => '8888-8888',
      'telefone_2' => '',
      'email' => 'noconferente@pdv.com.br',
      'site' => '',
      'nome_responsavel' => '',
      'telefone_responsavel' => '',
      'banco_id' => '1',
      'agencia' => '111',
      'conta' => '2222',
      'observacao' => '',
      'sexo' => 'm',
      'cargo' => 'quebra_galho',
     ]);
    return $conferente->id;
  }
  }
}

