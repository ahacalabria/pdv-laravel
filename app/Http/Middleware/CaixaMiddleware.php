<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Redirect;
use Session;

class CaixaMiddleware{
  protected $auth;

public function __construct(Guard $auth){
  $this->auth = $auth;
}
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
      if($this->auth->guest()){
        if($resquest->ajax()){
          return response('Unauthorized.', 401);
        }else{
          return redirect()->guest('login');
        }
      }else{
        if ($this->auth->user()->level != 'caixa'){
            Session::flash('message', "O usuário não é caixa!");
            return Redirect::back();
        }
        return $next($request);
      }
  }
}
