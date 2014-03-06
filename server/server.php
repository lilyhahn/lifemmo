<?php
require('LifeServer.php');
$serv = new LifeServer(30, 30);
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
    		sleep(5);
    	}
    }
    eval('$serv->' . $line . ";");
}
?>