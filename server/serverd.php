#!/usr/bin/php
<?php
require('LifeConsole.php');
$rule = new Rule(array(2, 3), array(3));
$serv = new LifeConsole(30, 30, $rule);
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");
while(1){
    try{
		$serv->start();
	}
	catch(Exception $e){
		echo "Error in ", $e->getFile(), " at ", $e->getLine(), ": ", $e->getMessage(), "\n";
	}
}
?>