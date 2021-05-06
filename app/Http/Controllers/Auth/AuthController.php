<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Response;
use Auth;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'pessoa_id' => $data['pessoa_id'],
            'level' => $data['level'],
            'password' => bcrypt($data['password']),
            'limite_dinheiro' => $data['limite_dinheiro'],
            'limite_porcetagem' => $data['limite_porcetagem']
        ]);
    }

    public function showLoginForm(){
      if(Auth::user() == NULL){
        return view('auth.login');
      }else{
        return Redirect::back();
      }
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function login(Request $request)
    // {
    //     $this->validate($request, [
    //         'email' => 'required|email', 'password' => 'required'
    //     ]);

    //     $credentials = $this->getCredentials($request);
    //     if (Auth::attempt($credentials, $request->has('remember'))) {
    //         return Response::json(['data' => ['success' => true, 'msg' => 'Login realizado com sucesso!']])
    //         ->header('Content-Type', 'application/json');
    //     }

    //     return Response::json(['data' => ['success' => false, 'msg' => 'Falha na autenticação!']])
    //     ->header('Content-Type', 'application/json');

    // }

    public function store()
    {
        $user = new User;
        $user->name = Input::get('name');
        $user->email = Input::get('email');
        $user->pessoa_id = Input::get('pessoa_id');
        $user->level = Input::get('level');
        $user->password  = bcrypt(Input::get('password'));
        $user->limite_dinheiro  = Input::get('limite_dinheiro');
        $user->limite_porcetagem  = Input::get('limite_porcetagem');
        $user->save();
        return view('auth.users');
    }
}
