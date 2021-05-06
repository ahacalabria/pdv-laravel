<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Pessoa;
use Redirect;
use View;
use Validator;
use Session;
use Auth;
use Datatables;

class UsersController extends Controller
{

  public function index()
  {
      return view('auth.users');
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
      $users = User::lists('pessoa_id');
      $funcionarios = Pessoa::where('tipo_cadastro','funcionario')->whereNotIn('id', $users)->get();
      return View::make('auth.register')->with('funcionarios',$funcionarios);
    }
  }
  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
      // $unidade = Unidade::create(Input::all());
      // return view('produtos.unidades');
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
          $users = User::with('funcionario')->orderBy('id')->get();
          $users = json_encode($users);
          return $users;
        }else{
          $user = User::with('funcionario')->orderBy('id')->get();
          $user = json_encode($user);
          return $user;
        }
      }

      public function datatables(){
        $users = User::with('funcionario')->orderBy('id');
        return Datatables::of($users)->make(true);
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
      $user = User::find($id);
      return View::make('auth.edit')->with('user', $user);
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
    $user = User::find($id);
    return View::make('auth.view')->with('user', $user);
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

      $validator = $this->validator(Input::all());

      // // process the login
      if ($validator->fails()) {
          return Redirect::to('users/' . $id . '/edit')
              ->withErrors($validator);
              // ->withInput(Input::except('password'));
      } else {
          // store
          $user = User::find($id);
          if(!empty(Input::get('email')))
            $user->email = Input::get('email');
          if(!empty(Input::get('password')))
            $user->password = bcrypt(Input::get('password'));
          $user->save();

          // redirect
          Session::flash('message', 'Usuário atualizado com sucesso!');
          return Redirect::to('users');
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
    // delete
    if (Auth::user()->level == 'vendedor' || Auth::user()->level == 'caixa' || Auth::user()->id == $id)
        return view('errors.302');
    else{
        $user = User::find($id);
        $user->delete();
        Session::flash('message', 'Usuário apagado com sucesso!');
        return Redirect::to('users');
      }

      // redirect
      // Session::flash('message', 'Successfully deleted the unit!');
      // return Redirect::to('unidades');
  }
  protected function validator(array $data)
  {
      return Validator::make($data, [
          'name' => 'sometimes|max:255',
          'email' => 'sometimes|email|max:255|unique:users',
          'limite_dinheiro' => 'required',
          'limite_porcetagem' => 'required',
          'password' => 'sometimes|min:6|confirmed',
      ]);
  }
}
