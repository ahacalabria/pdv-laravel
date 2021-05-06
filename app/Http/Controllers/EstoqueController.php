<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Produto;
use App\Nota;
use App\Movimentacao;
use Session;
use Redirect;
use Auth;
use Datatables;

class EstoqueController extends Controller
{
  public function __construct(){
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
        $total_prod = Produto::sum('quantidade_estoque');
        return view('estoque.index',compact('total_prod',$total_prod));
      }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
          return view('errors.302');
      else
        return view('estoque.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        // dd($request->all());
        $size = count($request->produto_id);
        $produto_id_list = Array();
        for($i=0;$i<$size;$i++){
          if($request->produto_id[$i] == ""){//novo produto
            $new_produto = array(
              'codigo' => $request->codigo[$i],
              'codigo_ncm' => $request->codigo_ncm[$i],
              'titulo' => $request->titulo[$i],
              'unidade_id' => $request->unidade_id[$i],
              'quantidade_estoque' => $request->quantidade_estoque[$i],
              'custo' => $request->custo_produto[$i],
              'preco' => $request->preco_produto[$i],
              'frete' => $request->frete_produto[$i],
              'descricao' => $request->descricao[$i],
              'pessoa_id' => $request->pessoa_fornecedor_id,
            );
            $produto = Produto::create($new_produto);
            $temp = $request->impostos_id[$i];
            $impostos_id = Array();
            $impostos_id=explode(',',$temp);
            $produto->impostos()->sync($impostos_id);
            $movimentacao = Movimentacao::create([
              'numero_nota' => $request->codigo_nota,
              'emitente_destinatario' => 'Entrada Nota',
              'valor_unitario' => $produto->preco,
              'valor_total' => $produto->preco,
              'quantidade' => $produto->quantidade_estoque,
              'estoque' => $produto->quantidade_estoque,
              ]);
              $produto->movimentacoes()->sync([$movimentacao->id],false);
            $produto_id_list[] = $produto->id;
          }else{//editar produto
            $produto = Produto::find($request->produto_id[$i]);

            $movimentacao = Movimentacao::create([
              'numero_nota' => $request->codigo_nota,
              'emitente_destinatario' => 'Entrada Nota',
              'valor_unitario' => $produto->preco,
              'valor_total' => $produto->preco,
              'quantidade' => ($request->quantidade_estoque[$i]) - ($produto->quantidade_estoque),
              'estoque' => $request->quantidade_estoque[$i],
              ]);
              $produto->movimentacoes()->sync([$movimentacao->id],false);

            $produto->codigo = $request->codigo[$i];
            $produto->codigo_ncm = $request->codigo_ncm[$i];
            $produto->titulo = $request->titulo[$i];
            $produto->descricao = $request->descricao[$i];
            $produto->unidade_id = $request->unidade_id[$i];
            $produto->pessoa_id = $request->pessoa_fornecedor_id;
            $produto->quantidade_estoque = $request->quantidade_estoque[$i];
            $produto->custo = $request->custo_produto[$i];
            $produto->frete = $request->frete_produto[$i];
            $produto->preco = $request->preco_produto[$i];
            $temp = $request->impostos_id[$i];
            $impostos_id = Array();
            $impostos_id=explode(',',$temp);
            $produto->impostos()->sync($impostos_id);
            $produto->save();
            $produto_id_list[] = $produto->id;
          }
        }

        $var = $request->data_emissao;
        $date = str_replace('/', '-', $var);
        $data_emissao_f = date('Y-m-d H:i', strtotime($date));

        $var = $request->data_entrada;
        $date = str_replace('/', '-', $var);
        $data_entrada_f = date('Y-m-d H:i', strtotime($date));
        $nota = Nota::create(array(
          'codigo' => $request->codigo_nota,
          'data_emissao' => $data_emissao_f,
          'data_entrada' => $data_entrada_f,
          'valor_frete' => $request->valor_frete_nota,
          'valor_total' => $request->valor_total_nota,
          // 'pessoa_id' => Input::get('pessoa_funcionario_id'),
        ));
        $nota->produtos()->sync($produto_id_list);
        Session::flash('message','Entrada de produtos por nota realizada com sucesso!');
        return Redirect::to('estoque');
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
        $produtos = Produto::with('pessoa')->with('categorias')->orderBy('id','desc')->get();
        $produtos = json_encode($produtos);
        return $produtos;
      }else{
        $produtos = Produto::where('id',$id)->get();
        $produtos = json_encode($produtos);
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
