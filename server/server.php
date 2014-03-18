<?php
require('LifeServer.php');
$rule = new Rule(array(2, 3), array(3));
$serv = new LifeServer(30, 30, $rule);
#$serv->init();
#$serv->draw(5, 20, 1);
#$serv->draw(5, 19, 1);
#$serv->generate();

while(1){
	$line = readline("> ");
    readline_add_history($line);
    if($line == "start"){
    	while(1){
    		$serv->generate();
    		//sleep(5);
    	}
    }
    eval('$serv->' . $line . ";");
}
?>