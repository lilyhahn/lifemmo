<?php
require('LifeServer.php');
class LifeConsole extends LifeServer{
	//some user friendly functions on top of LifeServer
	public function quit(){
		exit();
	}
	public function start(){
		while(1){
			$this->generate();
		}
	}
	public function help(){
		echo "init(): writes empty cells into database.\n
draw(x, y, state): draws a cell\n
generate(): steps one generation\n
start(): starts generation\n
quit(): quits\n";
	}
}
?>