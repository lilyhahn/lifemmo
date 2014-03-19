<?php
class ServerEvents{

	private $collection;

	public $paused = false;

	public function __construct($c){
		$this->collection = $c;
	}

	public function pollEvents(){
		$events = iterator_to_array($this->collection->find());
		foreach($events as $event){
			if($event["command"] == "pause"){
				$this->paused = true;
				$this->collection->remove(array("_id" => $event["_id"]));
			}
			if($event["command"] == "resume"){
				$this->paused = false;
				$this->collection->remove(array("_id" => $event["_id"]));
			}
		}
	}
}	
?>