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
	public function init(){
		for($i = 0; $i < $this->sizex; $i++){
			for($j = 0; $j < $this->sizey; $j++){
				$cell = array( "x" => $i, "y" => $j, "state" => 0);
				$this->collection->insert($cell);
			}
		}
		$this->db->createCollection("events");
		$this->connection->selectCollection('lifemmo', 'state')->insert(array("paused" => false));
	}
	public function help(){
		echo "init(): writes empty cells into database.\n
draw(x, y, state): draws a cell\n
generate(): steps one generation\n
start(): starts generation\n
quit(): quits\n
setRule(r): sets rule\n";
	}
	public function __construct($sx, $sy, $r){
		parent::__construct($sx, $sy, $r);
		$this->connection->selectCollection('lifemmo', 'state')->findAndModify(
					array("paused" => true),
					array('$set' => array("paused" => false)),
					null,
					null
		);
		$this->events = new ServerEvents($this->connection->selectCollection('lifemmo', 'events'), $this->connection->selectCollection('lifemmo', 'state'));
	}
}
?>