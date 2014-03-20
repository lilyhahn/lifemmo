<?php
class ServerEvents{

	private $collection;
	private $stateCollection;

	public $paused = false;

	public function __construct($c, $sc){
		$this->collection = $c;
		$this->stateCollection = $sc;
	}

	public function pollEvents(){
		$events = iterator_to_array($this->collection->find());
		foreach($events as $event){
			if($event["command"] == "pause"){
				$this->paused = true;
				$this->collection->remove(array("_id" => $event["_id"]));
				$this->stateCollection->findAndModify(
					array("paused" => false),
					array('$set' => array("paused" => true)),
					null,
					null
				);
			}
			if($event["command"] == "resume"){
				$this->paused = false;
				$this->collection->remove(array("_id" => $event["_id"]));
				$this->stateCollection->findAndModify(
					array("paused" => true),
					array('$set' => array("paused" => false)),
					null,
					null
				);
			}
		}
	}
}	
?>