<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Produto;

class ImportController extends Controller
{
    public function importarProdutos()
	{
	    // Uncomment the below to wipe the table clean before populating
	     //DB::table('books')->delete();

	            function csv_to_array($filename='', $delimiter=',')
	                    {
	                        if(!file_exists($filename) || !is_readable($filename))
	                            return FALSE;
	                     
	                        $header = NULL;
	                        $data = array();
	                        if (($handle = fopen($filename, 'r')) !== FALSE)
	                        {
	                            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
	                            {
	                                if(!$header)
	                                    $header = $row;
	                                else
	                                    //$data[] = array_combine($header, $row);
	                                	$count = '';
	                                	$count = min(count($header), count($row));
	                                	$data[] = array_combine(array_slice($header, 0, $count), array_slice($row, 0, $count));
	                            }
	                            fclose($handle);
	                        }
	                        return $data;
	                    }

	                    /****************************************
	                    * CSV FILE SAMPLE *
	                    ****************************************/
	                    // id,subdireccion_id,idInterno,area,deleted_at,created_at,updated_at
	                    // ,1,4,AREA MALAGA OCC.,,2013/10/13 10:27:52,2013/10/13 10:27:52
	                    // ,1,2,AREA MALAGA N/ORIENT,,2013/10/13 10:27:52,2013/10/13 10:27:52
	                     
	                    $csvFile = public_path().'/import/produtos.csv';
	                    $areas = csv_to_array($csvFile);
	                    $oi = array();
	                    
	                    foreach ($areas as $key => $prod) {
	                    	if($key > 2){
	                    		// print_r($key);
	                    		// print_r($produto);
	                    		// foreach ($produto as $j => $prod) {
	                    		$prod_update = Produto::where('codigo',$prod['CDPRO'])->get();
	                    		$prod_update[0]->titulo = $prod['NMPRO'];
	                    		$prod_update[0]->save();
	                    			// $new_prod = Produto::create(array(
	                    			// 	'titulo' => $prod['NMPRO'],
	                    			// 	'preco' => $prod['PREVE'],
	                    			// 	'custo' => $prod['PRECU'],
	                    			// 	'quantidade_estoque' => $prod['SALDO'],
	                    			// 	'descricao' => $prod['INFORMACOES_COMPLEMENTARES'],
	                    			// 	'codigo' => $prod['CDPRO'],
	                    			// 	'pessoa_id' => '14',
	                    			// 	'unidade_id' => '1',
	                    			// 	'codigo_ncm' => ($key),
	                    			// 	));
	                    		// }
	                    	}
	                    	
	                    }

	    // // Uncomment the below to run the seeder
	    // DB::table('books')->insert($areas);
	}

	public function importarClientes()
	{
	    // Uncomment the below to wipe the table clean before populating
	     //DB::table('books')->delete();

	            function csv_to_array($filename='', $delimiter=',')
	                    {
	                        if(!file_exists($filename) || !is_readable($filename))
	                            return FALSE;
	                     
	                        $header = NULL;
	                        $data = array();
	                        if (($handle = fopen($filename, 'r')) !== FALSE)
	                        {
	                            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
	                            {
	                                if(!$header)
	                                    $header = $row;
	                                else
	                                    //$data[] = array_combine($header, $row);
	                                	$count = '';
	                                	$count = min(count($header), count($row));
	                                	$data[] = array_combine(array_slice($header, 0, $count), array_slice($row, 0, $count));
	                            }
	                            fclose($handle);
	                        }
	                        return $data;
	                    }

	                    /****************************************
	                    * CSV FILE SAMPLE *
	                    ****************************************/
	                    // id,subdireccion_id,idInterno,area,deleted_at,created_at,updated_at
	                    // ,1,4,AREA MALAGA OCC.,,2013/10/13 10:27:52,2013/10/13 10:27:52
	                    // ,1,2,AREA MALAGA N/ORIENT,,2013/10/13 10:27:52,2013/10/13 10:27:52
	                     
	                    $csvFile = public_path().'/import/clientes.csv';
	                    $areas = csv_to_array($csvFile);
	                    foreach ($areas as $key => $cliente) {
	                    	if($key > 2){
	                    		// print_r($key);
	                    		 // print_r($cliente);
	                    		// foreach ($produto as $j => $prod) {

	                    		if(strlen( $prod['NMPRO'] ) === 11)
	                    			$new_prod = Pessoa::create(array(
	                    				'tipo_cadastro' => $prod['NMPRO'],
	                    				'tipo' => $prod['PREVE'],
	                    				'nome' => $prod['PRECU'],
	                    				'sobrenome' => $prod['SALDO'],
	                    				'nome_fantasia' => $prod['INFORMACOES_COMPLEMENTARES'],
	                    				'codigo' => $prod['CDPRO'],
	                    				'pessoa_id' => '14',
	                    				'unidade_id' => '1',
	                    				'codigo_ncm' => ($key),
	                    				));
	                    		// }
	                    	}
	                    	
	                    }

	    // // Uncomment the below to run the seeder
	    // DB::table('books')->insert($areas);
	}
}
