<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use App\Pessoa;
use App\Cidade;
use App\Estado;
use App\Banco;
use App\User;
use Session;
use Redirect;
use Auth;
use Datatables;

use OwenIt\Auditing\Log;

class PessoasController extends Controller
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
  public function index($tipo = 'all')
  {
    if (Auth::user()->level == 'vendedor' && $tipo != "cliente")
      return view('errors.302');
    else{
      switch ($tipo) {
        case 'cliente':
          return view('pessoas.cliente');
        break;
        case 'fornecedor':
          return view('pessoas.fornecedor');
        break;
        case 'funcionario':
          return view('pessoas.funcionario');
        break;
      }
    }
  }

  public function getPessoaTipo($tipo = null){
    if($tipo == 'funnologin'){
      $users = User::lists('pessoa_id');
      $pessoas = Pessoa::where('tipo_cadastro','funcionario')->whereNotIn('id', $users)->get();
    }else{
      if($tipo == "cliente")
        $pessoas = Pessoa::where('tipo_cadastro', $tipo)->get();
      else
        $pessoas = Pessoa::orderBy('updated_at', 'DESC')->where('tipo_cadastro', $tipo)->get();
    }
    return json_encode($pessoas);
    // return view('pessoas.index',compact('pessoas'));
  }

  public function getPessoaTipoFornecedorDatatables(){
    $pessoas = Pessoa::orderBy('updated_at', 'DESC')->where('tipo_cadastro', "fornecedor");
    return Datatables::of($pessoas)->make(true);
  }
  public function getPessoaTipoFuncionarioDatatables(){
    $pessoas = Pessoa::orderBy('updated_at', 'DESC')->where('tipo_cadastro', "funcionario");
    return Datatables::of($pessoas)->make(true);
  }
  public function getPessoaTipoClienteDatatables(){
    $pessoas = Pessoa::orderBy('updated_at', 'DESC')->where('tipo_cadastro', "cliente");
    return Datatables::of($pessoas)->make(true);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    if ((Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa') && Input::get('tipo') != "cliente")
        return view('errors.302');
    else {
        $estados = Estado::orderBy('id')->get();
        $bancos = Banco::orderBy('id')->get();
        $tipo = array(
          'f' => 'Pessoa Física',
          'j' => 'Pessoa Jurídica',
          );
        return view('pessoas.create',compact('estados', 'tipo', 'bancos'));
    }
  }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
      $validator = $this->validar();
      // $validator->sometimes('cpf', 'cpf|unique:pessoa,cpf,NULL,id,tipo_cadastro,'.Input::get('tipo_cadastro'), function($input)
      // {
      //     return !empty($input->cpf);
      // });
      // $validator->sometimes('rg', 'unique:pessoa,rg,NULL,id,tipo_cadastro,'.Input::get('tipo_cadastro'), function($input)
      // {
      //     return !empty($input->rg);
      // });
      // $validator->sometimes('email', 'email|unique:pessoa,email,NULL,id,tipo_cadastro,'.Input::get('tipo_cadastro'), function($input)
      // {
      //     return !empty($input->email);
      // });
      // $validator->sometimes('cnpj', 'cnpj|unique:pessoa,cnpj,NULL,id,tipo_cadastro,'.Input::get('tipo_cadastro'), function($input)
      // {
      //     return !empty($input->cnpj);
      // });
      // $validator->sometimes('ie', 'unique:pessoa,ie,NULL,id,tipo_cadastro,'.Input::get('tipo_cadastro'), function($input)
      // {
      //     return !empty($input->ie);
      // });
      $tipo = Input::get('tipo_cadastro');
      // process the login
      if ($validator->fails()) {
        $tipo_cadastro = array(
        'fornecedor' => 'Fornecedor',
        'funcionario' => 'Funcionário',
        'cliente' => 'Cliente',
        );
      $tipo = array(
        'f' => 'Pessoa Física',
        'j' => 'Pessoa Jurídica',
        );

          return Redirect::to('pessoa/create?tipo='.Input::get('tipo_cadastro'))
            ->with('tipo_cadastro',$tipo_cadastro)
            ->with('tipo',$tipo)
              ->withErrors($validator->errors())
              ->withInput();
      } else {
        $pessoa = Pessoa::create(Input::all());
        $var = Input::get('data_nascimento');
        $date = str_replace('/', '-', $var);
        $pessoa->data_nascimento = date('Y-m-d', strtotime($date));
        $pessoa->save();
        // redirect
        Session::flash('message', 'Pessoa salva com sucesso!');
        return Redirect::to('pessoas/'.$tipo);
      }
  }
  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */

  public function getcidades($estado_id){
    // if (Request::ajax()){
        // $text = \Request::input('textkey');
        $cidades = Cidade::orderBy('id')->where('estado_id', '=', $estado_id)->get();
        $cidades = json_encode($cidades);
        return $cidades;
    // }
  }
  public function show($id)
  {
    if($id === 'all') {
      $pessoa = Pessoa::orderBy('id', 'DESC')->get();
      $pessoa = json_encode($pessoa);
      return $pessoa;
    }else{
      $pessoa = Pessoa::where('id',$id)->get();
      $pessoa = json_encode($pessoa);
      return $pessoa;
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
      // get the nerd
      $pessoa = Pessoa::findOrFail($id);
      $estados = Estado::orderBy('id')->get();
      $bancos = Banco::orderBy('id')->get();
      $tipo_cadastro = array(
        'fornecedor' => 'Fornecedor',
        'funcionario' => 'Funcionário',
        'cliente' => 'Cliente',
        );
      $tipo = array(
        'f' => 'Pessoa Física',
        'j' => 'Pessoa Jurídica',
        );

      // show the edit form and pass the nerd
      return View::make('pessoas.view')
          ->with('pessoa', $pessoa)->with('estados', $estados)->with('bancos', $bancos)->with('tipo_cadastro', $tipo_cadastro)->with('tipo', $tipo);
  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    // if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa')
        // return view('errors.302');
    // else{
        $pessoa = Pessoa::find($id);
        $estados = Estado::orderBy('id')->get();
        $bancos = Banco::orderBy('id')->get();
        $tipo_cadastro = array(
          'fornecedor' => 'Fornecedor',
          'funcionario' => 'Funcionário',
          'cliente' => 'Cliente',
          );
        $tipo = array(
          'f' => 'Pessoa Física',
          'j' => 'Pessoa Jurídica',
          );

        // show the edit form and pass the nerd
        return View::make('pessoas.edit')
            ->with('pessoa', $pessoa)->with('estados', $estados)->with('bancos', $bancos)->with('tipo_cadastro', $tipo_cadastro)->with('tipo', $tipo);
    // }
  }
  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
      // validate
      // read more on validation at http://laravel.com/docs/validation
      $validator = $this->validar();

      // process the login
      if ($validator->fails()) {
          return Redirect::to('pessoa/' . $id . '/edit')
              ->withErrors($validator->errors())
              ->withInput();
      } else {
          // store
          $pessoa = Pessoa::find($id);
          $pessoa->tipo_cadastro = Input::get('tipo_cadastro');
          $pessoa->tipo = Input::get('tipo');
          $pessoa->sexo = Input::get('sexo');
          $pessoa->nome = Input::get('nome');
          $pessoa->cargo = Input::get('cargo');
          $pessoa->sobrenome = Input::get('sobrenome');
          $pessoa->nome_fantasia = Input::get('nome_fantasia');
          $pessoa->razao_social = Input::get('razao_social');
          $pessoa->cpf = Input::get('cpf');
          $pessoa->cnpj = Input::get('cnpj');
          $pessoa->rg = Input::get('rg');
          $pessoa->ie = Input::get('ie');

          $var = Input::get('data_nascimento');
          $date = str_replace('/', '-', $var);
          $pessoa->data_nascimento = date('Y-m-d', strtotime($date));

          $pessoa->estado_id = Input::get('estado_id');
          $pessoa->cidade_id = Input::get('cidade_id');
          $pessoa->endereco = Input::get('endereco');
          $pessoa->bairro = Input::get('bairro');
          $pessoa->cep = Input::get('cep');
          $pessoa->telefone_1 = Input::get('telefone_1');
          $pessoa->telefone_2 = Input::get('telefone_2');
          $pessoa->email = Input::get('email');
          $pessoa->site = Input::get('site');
          $pessoa->nome_responsavel = Input::get('nome_responsavel');
          $pessoa->telefone_responsavel = Input::get('telefone_responsavel');
          $pessoa->banco_id = Input::get('banco_id');
          $pessoa->agencia = Input::get('agencia');
          $pessoa->conta = Input::get('conta');
          $pessoa->observacao = Input::get('observacao');
          $pessoa->save();
          $tipo = Input::get('tipo_cadastro');
          // redirect
          Session::flash('message', 'Pessoa atualizada com sucesso!');
          return Redirect::to('pessoas/'.$tipo);
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
    if (Auth::user()->level == 'root' || Auth::user()->level == 'administrador'){
      $pessoa = Pessoa::find($id);
      $tipo = $pessoa->tipo;
      $pessoa->delete();
      Session::flash('message', 'Pessoa apagada com sucesso!');
      return Redirect::to('pessoas/'.$tipo);
    }
    else
        return view('errors.302');

  }
  /**
   * Validating fields.
   *
   * @return Validator
   */
  public function validar(){
    if(Input::get('tipo') === 'j'):
      $rules = array(
            'tipo_cadastro' => 'in:fornecedor,funcionario,cliente',
            'tipo' => 'in:f,j',
            'sexo' => 'in:f,m',
            // 'nome_fantasia' => 'required',
            'razao_social' => 'required',
            //  'cnpj' => 'some|cnpj',
            // 'ie' => 'required',
            'estado_id' => 'required|numeric',
            'cidade_id' => 'required|numeric',
            // 'endereco' => 'required',
            // 'bairro' => 'required',
            // 'cep' => '',
            // 'telefone_1' => 'required',
            // 'telefone_2' => '',
            // 'email' => 'required|email',
            // 'site' => '',
            // 'nome_responsavel' => 'required',
            // 'telefone_responsavel' => 'required',
            'banco_id' => 'required|numeric',
            // 'agencia' => '',
            // 'conta' => '',
            // 'observacao' => '',
        );
    elseif(Input::get('tipo') === 'f'):
      $rules = array(
            'tipo_cadastro' => 'in:fornecedor,funcionario,cliente',
            'tipo' => 'in:f,j',
            'nome' => 'required',
            'sexo' => 'in:f,m',
            // 'sobrenome' => 'required',
            // 'cpf' => 'cpf',
            // 'rg' => 'required',
            // 'data_nascimento' => 'required',
            'estado_id' => 'required|numeric',
            'cidade_id' => 'required|numeric',
            // 'endereco' => 'required',
            // 'bairro' => 'required',
            // 'cep' => '',
            // 'telefone_1' => 'required',
            // 'telefone_2' => '',
            // 'email' => 'required|email',
            // 'site' => '',
            'banco_id' => 'required|numeric',
            // 'agencia' => '',
            // 'conta' => '',
            // 'observacao' => '',
        );
    endif;

      $v = Validator::make(Input::all(), $rules);

      return $v;
  }

  public function log (){
        // $logs = Log::with('user')->orderBy('created_at','Desc')->get(); // Get logs of Post
        return view('pessoas.auditing');//, compact('logs'));
  }
  public function logdatatables(){
    $logs = Log::with('user');//->orderBy('user.created_at','Desc');
    return Datatables::of($logs)->make(true);
  }

   public function alllog (){
        $logs = Log::with('user')->orderBy('created_at','Desc')->get(); // Get logs of Post
        return response()->json($logs);
  }

  public function getLog($id){
        $logs = Log::with('user')->orderBy('created_at','Desc')->find($id); // Get logs of Post
        return response()->json($logs);
  }


}
