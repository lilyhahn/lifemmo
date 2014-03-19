<?php
require('LifeServer.php');
require('ServerEvents.php');
class LifeConsole extends LifeServer{
	//some user friendly functions on top of LifeServer
	private $events;
	public function quit(){
		exit();
	}
	public function start(){
		while(1){
			$this->events->pollEvents();
			if(!$this->events->paused)
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
	public function __construct($sx, $sy, $r){
		parent::__construct($sx, $sy, $r);
		$this->events = new ServerEvents($this->connection->selectCollection('lifemmo', 'events'));
	}
}
?>