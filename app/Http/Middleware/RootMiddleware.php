<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Redirect;
use Session;

class RootMiddleware
{
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
          if ($this->auth->user()->level != 'root'){
              Session::flash('message', "Usuário com permissões limitadas!");
              return Redirect::to('home');
          }
          return $next($request);
        }
    }
}
