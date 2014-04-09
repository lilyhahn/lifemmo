#!/usr/bin/php
<?php
require('LifeConsole.php');
$rule = new Rule(array(2, 3), array(3));
$serv = new LifeConsole(50, 50, $rule);
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");
$serv->start();
?>