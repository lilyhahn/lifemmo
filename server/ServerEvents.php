<?php
class ServerEvents{

	private $server;
	private $events;
	private $stateCollection;

	public $paused = false;

	public function __construct(LifeServer $s){
		$this->server = $s;
		$this->events = $this->server->connection->selectCollection('lifemmo', 'events');
		$this->stateCollection = $this->server->connection->selectCollection('lifemmo', 'state');
	}

	public function pollEvents(){
		$events = iterator_to_array($this->events->find());
		foreach($events as $event){
			if($event["command"] == "pause"){
				$this->paused = true;
				$this->events->remove(array("_id" => $event["_id"]));
				$this->stateCollection->findAndModify(
					array("paused" => false),
					array('$set' => array("paused" => true)),
					null,
					null
				);
			}
			if($event["command"] == "resume"){
				$this->paused = false;
				$this->events->remove(array("_id" => $event["_id"]));
				$this->stateCollection->findAndModify(
					array("paused" => true),
					array('$set' => array("paused" => false)),
					null,
					null
				);
			}
			if($event["command"] == "changeRule"){
				$r = new Rule($event["rule"]["s"], $event["rule"]["b"]);
				$this->server->setRule($r);
				$this->events->remove(array("_id" => $event["_id"]));
			}
		}
	}
}	
?>