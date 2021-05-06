<?php
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
  error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Banco;
// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route; 

Route::auth();

    // Authentication Routes...
    //$this->get('login', 'Auth\AuthController@showLoginForm');
    //$this->post('login', 'Auth\AuthController@login');
    //$this->get('logout', 'Auth\AuthController@logout');

    // Password Reset Routes...
    $this->get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    $this->post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    $this->post('password/reset', 'Auth\PasswordController@reset');

    Route::get('/home', 'HomeController@index');
    Route::get('/getGraphs', 'HomeController@getGraphs');

    // Registration Routes...
    $this->get('register', 'UsersController@create');
    $this->post('register', 'Auth\AuthController@register');

  // Route::get('/teste2',['middleware' => 'root', function(){
  //   echo "root page!";
  // }]);
  Route::get('relatorios', 'PdfController@index');
  Route::get('/relatorio/{tipo}','PdfController@relatorio');
  Route::get('pdf/fornecedores', 'PdfController@fornecedores');
  Route::get('pdf/clientes', 'PdfController@clientes');
  Route::get('pdf/produtos', 'PdfController@produtos');
   Route::get('pdf/funcionarios', 'PdfController@funcionarios');
   Route::get('pdf/vendas', 'PdfController@vendas');
   Route::get('pdf/vendasresumido', 'PdfController@vendasresumido');
   Route::get('pdf/venda_cupom', 'PdfController@venda_cupom');
   Route::get('pdf/fluxodecaixa', 'PdfController@fluxodecaixa');
   Route::get('pdf/prodinter', 'PdfController@prodinter');
   Route::get('pdf/prodexter', 'PdfController@fluxodecaixa');

   Route::get('log', 'PessoasController@log');
   Route::get('log/datatables', 'PessoasController@logdatatables');
   Route::get('log/all', 'PessoasController@alllog');
   Route::get('getlog/{id}','PessoasController@getLog');

   Route::get('/importar/produtos','ImportController@importarProdutos');
   Route::get('/importar/clientes','ImportController@importarClientes');


    // Pessoa
    Route::resource('pessoa', 'PessoasController');

    Route::get('/', 'HomeController@index');

    Route::get('printcupom/{venda_id}', 'PrinterController@imprimir');
    Route::get('printcupom2/{venda_id}', 'PrinterController@imprimira4');

    Route::get('/home', 'HomeController@index');

    Route::get('/pessoas/{tipo}','PessoasController@index');
    Route::get('/pessoas/{id}/view/','PessoasController@visualizar');
    // Route::get('/pessoa/update','PessoasController@update');
    Route::get('/getpessoatipofornecedor/datatables','PessoasController@getPessoaTipoFornecedorDatatables');
    Route::get('/getpessoatipocliente/datatables','PessoasController@getPessoaTipoClienteDatatables');
    Route::get('/getpessoatipofuncionario/datatables','PessoasController@getPessoaTipoFuncionarioDatatables');
    Route::get('/getpessoatipo/{tipo}','PessoasController@getPessoaTipo');

    /* USUARIOS*/
    Route::get('users/datatables','UsersController@datatables');
    Route::resource('users','UsersController');
    Route::get('/users/{id}/view/','UsersController@visualizar');

    Route::post('user','Auth\AuthController@store');
    Route::get('/banks',function(){
      return Banco::orderBy('id')->get();
    });
    /* PRODUTOS */
    Route::get('produtos/datatables','ProdutosController@datatables');
    Route::resource('produtos','ProdutosController');
    Route::get('/produtos/{id}/view/','ProdutosController@visualizar');
    Route::get('/produtos/{id}/desabilitar/','ProdutosController@desabilitar');
    Route::get('/produtos/{id}/habilitar/','ProdutosController@habilitar');
    Route::get('/historico_busca_produto/{id}','ProdutosController@historico_busca_produto');
    
    Route::get('/taxageral','ProdutosController@taxageral');
    Route::post('/applytaxageral','ProdutosController@applytaxageral');

    Route::get('estoque/datatables','EstoqueController@datatables');
    Route::resource('estoque','EstoqueController');
    Route::get('cheques/datatables','ChequesController@datatables');
    Route::resource('cheques','ChequesController');
    /* Categorias PRODUTOS */
    Route::get('categorias/datatables','CategoriasController@datatables');
    Route::resource('categorias','CategoriasController');
    /* Subcategorias PRODUTOS */
    Route::resource('subcategorias','SubcategoriasController');
    Route::get('getSubcategoriasByCategoriaId/{id}','SubcategoriasController@getSubcategoriasByCategoriaId');
    // UNIDADES DE MEDIDA
    Route::get('unidades/datatables','UnidadesController@datatables');
    Route::resource('unidades','UnidadesController');
    /* IMPOSTO PRODUTOS */
    Route::get('impostos/datatables','ImpostosController@datatables');
    Route::resource('impostos','ImpostosController');
    /* TIPO PAGAMENTO  */
    Route::get('tipopagamentos/datatables','TipoPagamentosController@datatables');
    Route::resource('tipopagamentos','TipoPagamentosController');
    /* VENDA */
    Route::get('vendas/varejo','VendasController@varejo');
    // Route::get('vendas/varejo','VendasController@varejo');
    Route::get('vendas/datatables','VendasController@datatables');
    Route::resource('vendas','VendasController');

    Route::get('etiquetas','VendasController@etiquetas');
    // Route::get('corrigirvendasavista','VendasController@corrigirvendasavista');

    // Route::post('geraretiquetas','VendasController@geraretiquetas');



    // Route::get('vendas',['as' => 'vendas', 'uses' => 'VendasController@index']);
    // Route::get('vendas/create',['as' => 'vendas.create', 'uses' => 'VendasController@create']);
    // Route::get('vendas/{id}',['as' => 'vendas.show', 'uses' => 'VendasController@show']);
    // Route::get('vendas/{id}/edit',['as' => 'vendas.edit', 'uses' => 'VendasController@edit']);
    //
    // Route::post('vendas',['as' => 'vendas.store', 'uses' => 'VendasController@store']);
    // Route::put('vendas/{id}',['as' => 'vendas.update', 'uses' => 'VendasController@update']);
    // Route::delete('vendas/{id}',['as' => 'vendas.destroy', 'uses' => 'VendasController@destroy']);
    /* CAIXA */
    Route::get('parceladopagar/datatables','ParceladoPagarController@datatables');
    Route::resource('parceladopagar','ParceladoPagarController');
    Route::get('parceladoreceber/datatables','ParceladoReceberController@datatables');
    Route::resource('parceladoreceber','ParceladoReceberController');
    /* FINANCEIRO */
    // Route::resource('financeiro','FinanceiroController');
    Route::get('financeiro/{tipo}','FinanceiroController@index');
      /* FINANCEIRO Pagar*/
    Route::resource('financeiropagar','FinanceiroPagarController');
      /* FINANCEIRO Receber*/
    Route::resource('financeiroreceber','FinanceiroReceberController');

    Route::resource('pagamentovenda','FinanceiroReceberController@pagamentovenda');

    Route::get('/getcidades/{estado_id}', 'PessoasController@getcidades');
