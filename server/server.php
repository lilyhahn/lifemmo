#!/usr/bin/php
<?php
require('LifeConsole.php');
#apd_set_pprof_trace(".");
$rule = new Rule(array(2, 3), array(3));
$serv = new LifeConsole(30, 30, $rule);
#$serv->init();
#$serv->draw(5, 20, 1);
#$serv->draw(5, 19, 1);
#$serv->generate();
#var_dump($serv->getRule());
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");
while(1){
	$line = readline("> ");
    readline_add_history($line);
    /*if($line == "start"){
    	while(1){
    		$serv->generate();
    		//sleep(5);
    	}
    }*/
    try{
		eval('$serv->' . $line . ";");
	}
	catch(Exception $e){
		echo "Error in ", $e->getFile(), " at ", $e->getLine(), ": ", $e->getMessage(), "\n";
	}
}
?>