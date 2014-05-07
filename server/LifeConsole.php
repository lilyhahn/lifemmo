<?php
require('LifeServer.php');
require('ServerEvents.php');
class LifeConsole extends LifeServer{
	//oh god php why
	private $__friends = array('ServerEvents');

    public function __get($key)
    {
        $trace = debug_backtrace();
        if(isset($trace[1]['class']) && in_array($trace[1]['class'], $this->__friends)) {
            return $this->$key;
        }

        // normal __get() code here

        trigger_error('Cannot access private property ' . __CLASS__ . '::$' . $key, E_USER_ERROR);
    }

    public function __set($key, $value)
    {
        $trace = debug_backtrace();
        if(isset($trace[1]['class']) && in_array($trace[1]['class'], $this->__friends)) {
            return $this->$key = $value;
        }

        // normal __set() code here

        trigger_error('Cannot access private property ' . __CLASS__ . '::$' . $key, E_USER_ERROR);
    }
	//some user friendly functions on top of LifeServer
	private $events;
	public function quit(){
		exit();
	}
	public function start(){
		while(1){
			$this->events->pollEvents();
			if(!$this->events->paused){
				$this->generate();
				usleep(500 * 1000);
			}
			else{
				usleep(500 * 1000);
			}
		}
	}
	public function setRule($r){
		parent::setRule($r);
		$this->connection->selectCollection('lifemmo', 'state')->findAndModify(
			array("rule" => array('$exists' => true)),
			array('$set' => array("rule" => $this->rule))
		);
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
		$this->connection->selectCollection('lifemmo', 'state')->insert(array("rule" => $this->rule));
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
		$this->events = new ServerEvents($this);
	}
}
?>