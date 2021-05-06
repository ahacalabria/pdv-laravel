<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\AuditingTrait;

class Printer extends Model
{
    use AuditingTrait;

    public function getPrinters()
    {
        $response  = $this->runCommand( 'lpstat -p' );
        $printers  = array();

        foreach( $response as $row )
        {
            preg_match( '/printer\s(.*)\is/', $row, $printer );
            preg_match( '/is\s(.*)\./', $row, $statusCode );

            if( end( $printer ) )
            {
                $printers[] = array( 'name' => end( $printer ), 'status' => end( $statusCode ) );
            }
        }

        return array( 'printers' => $printers );
    }


    public function submit( $filename, $printerName = false, $capabilities = array() )
    {
        if( $printerName )
        {
            // $command = 'lp -d ' . $printerName . ' ';
            $command = 'NOTEPAD /P ';
        }
        else
        {
            $command = 'lpr ';
        }

        if( $capabilities )
        {
            foreach( $capabilities  as $cap )
            {
                $command .= '-o ' . $cap . ' ';
            }
        }

        if( $filename )
        {
            $command .= $filename;
        }

        $this->runCommand( $command );
    }


    protected function runCommand( $command )
    {
        exec( escapeshellcmd( $command ), $output );

        return $output;
    }
}
