<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Produto;
use App\ProdutoImposto;
use App\Pessoa;
use App\Unidade;
use App\Imposto;
use App\Movimentacao;
use App\User;
use Session;
use Redirect;
use Auth;
use Datatables;

class ProdutosController extends Controller
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
      return view('produtos.index');
    }
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
      return view('produtos.create');
  }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    // var_dump(Input::all());
    if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        return view('errors.302');
    else{
      $validator = $this->validar();

      // process the login
      if ($validator->fails()) {
         return Redirect::to('produtos/create')
              ->withErrors($validator->errors())
              ->withInput();
      }else{
      // if(empty($input->codigo)) $input->codigo=NULL;
      $produto = Produto::create(Input::all());
      if(!empty(Input::get('impostos_id'))){
        $temp = Input::get('impostos_id');
        $impostos_id = Array();
        $impostos_id=explode(',',$temp);
        $produto->impostos()->sync($impostos_id);
      }

      foreach($produto->impostos as $imposto){;
        $imposto->updated_at = date('Y-m-d H:i:s');
        $imposto->save();
      }

      if(!empty(Input::get('subcategorias'))) $produto->subcategorias()->sync(Input::get('subcategorias'));
      if(!empty(Input::get('categorias'))) $produto->categorias()->sync(Input::get('categorias'));
      $movimentacao = Movimentacao::create([
        'numero_nota' => '',
        'emitente_destinatario' => 'Entrada Avulsa',
        'valor_unitario' => $produto->preco,
        'valor_total' => $produto->preco,
        'quantidade' => $produto->quantidade_estoque,
        'estoque' => $produto->quantidade_estoque,
        ]);
        $produto->movimentacoes()->sync([$movimentacao->id],false);
      Session::flash('message', 'Produto cadastrado com sucesso!');
      return Redirect::to('produtos');
      }
    }
  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function visualizar($id)
  {
    $produto = Produto::with('pessoa')->with('unidade')->with('impostos')->findOrFail($id);
    return View::make('produtos.view')
      ->with('produto', $produto);
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
        $produtos = Produto::with('pessoa')->with('categorias')->orderBy('id','desc')->get();
        $produtos = json_encode($produtos);
        return $produtos;
      }else{
        $produtos = Produto::with('impostos')->with('unidade')->where('desabilitar',false)->where('id',$id)->get();
        $produtos = json_encode(["produtos"=>$produtos,"user"=>Auth::user()->level]);
        return $produtos;
      }
  }
  public function datatables(){
    $produtos = Produto::with('pessoa')->with('categorias')->orderBy('id','desc');
    return Datatables::of($produtos)->make(true);
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
      $produto = Produto::findOrFail($id);
      $fornecedores = Pessoa::where('tipo_cadastro','fornecedor')->orderBy('id')->get();
      $unidades = Unidade::orderBy('id')->get();
      return View::make('produtos.edit')
        ->with('produto', $produto)
        ->with('fornecedores', $fornecedores)
        ->with('unidades', $unidades);
      }
  }

  public function taxageral(){
    if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
      return view('errors.302');
    else
      return View::make('produtos.taxageral');
  }

  public function applytaxageral(){
    if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
      return view('errors.302');
    else{
      // $valor = 0
      $fornecedor_id = (int)Input::get('fornecedor_id');
      $signal = (int)Input::get('signal');
      $produtos = Produto::where('pessoa_id',$fornecedor_id)->with('impostos')->get();
      $message = "Produtos atualizados com sucesso!";
      if(count($produtos)===0) $message = "Produtos não encontrados para o fornecedor selecionado!";
      foreach ($produtos as $prod_temp) {

        $custo_prod = $prod_temp->custo;
        $imposto_tax = 0.0;
        $impostos = $prod_temp['impostos'];
        foreach ($impostos as $tax) $imposto_tax += (float) $tax['valor'];
        $custo_with_tax = $custo_prod/(1-($imposto_tax/100.0));
        $valor_agregado_new = (float)Input::get('valor_agregado');
        // if($signal===1) $agregado = (float)$prod_temp->valor_agregado + $valor_agregado_new;
        // else  $agregado = (float)$prod_temp->valor_agregado - $valor_agregado_new;
        if($signal===1){
          $custo_with_agregado = $prod_temp->preco/(1-($valor_agregado_new/100.0));
          $agregado = (float)$prod_temp->valor_agregado + $valor_agregado_new;
        } else {
          $custo_with_agregado = $prod_temp->preco*(1-($valor_agregado_new/100.0));
          $agregado = (float)$prod_temp->valor_agregado - $valor_agregado_new;
        } 
        
        $prod_temp->valor_agregado = $agregado;
        $prod_temp->preco = $custo_with_agregado;
        $prod_temp->save();
        // return json_encode($prod_temp);
        
      }
      return View::make('produtos.taxageral')->with('message', $message);
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
    // validate
      $validator = $this->validar($id);

      // process the login
      if ($validator->fails()) {
          return Redirect::to('produtos/' . $id . '/edit')
              ->withErrors($validator->errors())
              ->withInput();
      } else {
          // store
          $produto = Produto::find($id);

          $movimentacao = Movimentacao::create([
            'numero_nota' => '',
            'emitente_destinatario' => 'Entrada Avulsa',
            'valor_unitario' => $produto->preco,
            'valor_total' => $produto->preco,
            'quantidade' => (Input::get('quantidade_estoque')) - ($produto->quantidade_estoque),
            'estoque' => Input::get('quantidade_estoque'),
            ]);

          $produto->pessoa_id = Input::get('pessoa_id');
          $produto->codigo = Input::get('codigo');
          $produto->codigo_ncm = Input::get('codigo_ncm');
          $produto->titulo = Input::get('titulo');
          $produto->custo = Input::get('custo');
          $produto->preco = Input::get('preco');
          $produto->unidade_id = Input::get('unidade_id');
          $produto->quantidade_estoque = Input::get('quantidade_estoque');
          $produto->descricao = Input::get('descricao');
          $produto->valor_agregado = Input::get('valor_agregado');
          $temp = Input::get('impostos_id');
          if(!empty($temp)){
            $impostos_id = Array();
            $impostos_id=explode(',',$temp);
            $produto->impostos()->sync($impostos_id);
          }
          
          // return(1);
          $produto->movimentacoes()->sync([$movimentacao->id],false);
          if(!empty(Input::get('subcategorias'))) $produto->subcategorias()->sync(Input::get('subcategorias'));
          if(!empty(Input::get('categorias'))) $produto->categorias()->sync(Input::get('categorias'));
          $produto->save();
          
          foreach($produto->impostos as $imposto){;
            $imposto->updated_at = date('Y-m-d H:i:s');
            $imposto->save();
          }

          // redirect
          Session::flash('message', 'Produto atualizado com sucesso!');
          return Redirect::to('produtos');
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
    else{
    // delete
      $produto = Produto::find($id);
      $produto->delete();

      // redirect
      Session::flash('message', 'Produto deletado com sucesso!');
      return Redirect::to('produtos');
    }
  }
  /**
   * desabilitar produto.
   *
   * @param  int  $id
   * @return Response
   */
  public function desabilitar($id)
  {
    if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        return view('errors.302');
    else{
    // delete
      $produto = Produto::findOrFail($id);
      $produto->desabilitar = true;
      $produto->save();

      // redirect
      Session::flash('message', 'Produto desabilitado com sucesso!');
      return Redirect::to('produtos');
    }
  }
  /**
   * habilitar produto.
   *
   * @param  int  $id
   * @return Response
   */
  public function habilitar($id)
  {
    if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        return view('errors.302');
    else{
    // delete
      $produto = Produto::findOrFail($id);
      $produto->desabilitar = false;
      $produto->save();

      // redirect
      Session::flash('message', 'Produto habilitado com sucesso!');
      return Redirect::to('produtos');
    }
  }
  /*
  * Method: GET
  * Author: Afonso Henrique Anastácio Calábria
  * Body Request: prod_id
  */
  public function historico_busca_produto($id){
    $userID = Auth::user()->id; //pega o id do usuario logado
    
    $user = User::with('historico_busca')->find($userID); // pega o usuario completo com o seu historico de buscas
    
    $produto_id = $id; //pega o id do produto passado na requisicao
    
    $lista = $user->historico_busca()->getRelatedIds(); //pega todos os produtos buscados pelo usuario logado
    $limit = 10; //define um limite para o historico de cada usuario
    if(count($lista) >= $limit){ //se a lista atual de produtos buscados for maior ou igual ao limite
      if(in_array($produto_id,$lista->toArray())){ //se o produto buscado esta contigo na lista
        if (($key = array_search($produto_id, $lista->toArray())) !== false) { //busca a posicao dele na lista
          unset($lista[$key]); //remove ele da lista
        }
      }else{ //se o produto buscao nao esta na lista
        $lista = array_slice($lista,($limit-1)); //remove um elemento da lista com base no limite
      }
    }
    $user->historico_busca()->sync($lista->toArray()); //sincroniza a lista atual
    $user->historico_busca()->syncWithoutDetaching([$produto_id]); //add o ultimo produto buscado
    
    //filtra usando a funcao map as os atributos id e titulo dos produtos
    $produtos = Produto::whereIn('id',$lista)->get()->map(
      function ($produto) {
        return collect($produto->toArray())
          ->only(['id', 'titulo'])
          ->all();
      });

    //retorna o json dos produtos do historico
    return json_encode($produtos);
  
  }
  /**
   * Validating fields.
   *
   * @return Validator
   */
  public function validar($id=0){
      $rules = array(
            'pessoa_id' => 'required',
            // 'codigo' => 'required|unique:produto,codigo,'.$id,
            // 'codigo_ncm' => 'required|unique:produto,codigo_ncm,'.$id,
            'titulo' => 'required',
            'custo' => 'required',
            'preco' => 'required',
            // 'frete' => 'required',
            'quantidade_estoque' => 'required',
            // 'descricao' => 'required',
            'unidade_id' => 'required',
            // 'impostos_id' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        // $validator->sometimes('codigo', 'unique:produto,codigo,NULL,id', function($input)
        // {
        //     return !empty($input->codigo);
        // });
        // $validator->sometimes('codigo_ncm', 'unique:produto,codigo_ncm,NULL,id', function($input)
        // {
        //     return !empty($input->codigo_ncm);
        // });
      return $validator;
  }
}
