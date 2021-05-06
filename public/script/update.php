<?php

echo "<pre>ATUALIZANDO REPOSITORIO...</pre>";
//comandos
//git pull origin master
$output = shell_exec('cd /var/www/localhost/html/sistema-pdv-clever/ && git pull origin master');
sleep ( 4 );
var_dump($output);
echo "<pre>$output</pre>";

?>
