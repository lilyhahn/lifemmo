<?php
require('arraysearch.php');
require('Rule.php');
function checkCondition($items, $condition){
	foreach($items as $item){
		if($item == $condition)
			return true;
	}
	return false;
}
class LifeServer{
	protected $sizex;
	protected $sizey;
	protected $connection;
	protected $db;
	protected $collection;
	protected $rule;
	public function __construct($sx, $sy, $r){
		$this->sizex = $sx;
		$this->sizey = $sy;
		$this->connection = new MongoClient(getenv("MONGODB_URI"));
		$this->collection = $this->connection->selectCollection('lifemmo', 'cells');
		$this->rule = $r;
	}
	public function init(){
		for($i = 0; $i < $this->sizex; $i++){
			for($j = 0; $j < $this->sizey; $j++){
				$cell = array( "x" => $i, "y" => $j, "state" => 0);
				$this->collection->insert($cell);
			}
		}
	}
	public function draw($x, $y, $state){
		$this->collection->findAndModify(
			array("x" => $x, "y" => $y),
			array('$set' => array("state" => $state)),
			null,
			null
		);
	}
	public function find($x, $y){
		return $this->collection->findOne(array("x" => $x, "y" => $y));
	}
	private function neighborhood_sum($data, $x, $y){
		$nsum = 0;
		if($x < $this->sizex){
			$tmp = searchSubArray($data, "x", $x + 1, "y", $y);
			if($tmp["state"] == 1)
				$nsum++;
		}
		if($x > 0){
			$tmp = searchSubArray($data, "x", $x - 1, "y", $y);
			if($tmp["state"] == 1)
				$nsum++;
		}
		if($y < $this->sizey){
			$tmp = searchSubArray($data, "x", $x, "y", $y + 1);
			if($tmp["state"] == 1)
				$nsum++;
		}
		if($y > 0){
			$tmp = searchSubArray($data, "x", $x, "y", $y - 1);
			if($tmp["state"] == 1)
				$nsum++;
		}
		if($x < $this->sizex && $y < $this->sizey){
			$tmp = searchSubArray($data, "x", $x + 1, "y", $y + 1);
			if($tmp["state"] == 1)
				$nsum++;
		}
		if($x > 0 && $y > 0){
			$tmp = searchSubArray($data, "x", $x - 1, "y", $y - 1);
			if($tmp["state"] == 1)
				$nsum++;
		}
		if($x < $this->sizex && $y > 0){
			$tmp = searchSubArray($data, "x", $x + 1, "y", $y - 1);
			if($tmp["state"] == 1)
				$nsum++;
		}
		if($x > 0 && $y < $this->sizey){
			$tmp = searchSubArray($data, "x", $x - 1, "y", $y + 1);
			if($tmp["state"] == 1)
				$nsum++;
		}
		return $nsum;
	}
	public function generate(){
		$changes = array();
		$data = iterator_to_array($this->collection->find());
		//step one: find changes we need to make and put details in an array
		for($i = 0; $i < $this->sizex; $i++){
			for($j = 0; $j < $this->sizey; $j++){
				$nsum = $this->neighborhood_sum($data, $i, $j);
				/*if($nsum == 3){
					echo "nsum: $nsum\n";
					echo "thisdata: $thisdata\n";
				}*/
				$thisdata = searchSubArray($data, "x", $i, "y", $j);
				$thisdata = $thisdata["state"];
				if(checkCondition($this->rule->s, $nsum) && $thisdata == 1){
					continue;
				}
				else if(checkCondition($this->rule->b, $nsum) && $thisdata == 0){
					$changes[] = array("x" => $i,
					"y" => $j,
					"state" => 1);
				}
				else if($thisdata == 1){
					$changes[] = array("x" => $i,
					"y" => $j,
					"state" => 0);
				}
			}
		}
		//step two: apply changes
		foreach($changes as $change){
			$this->draw($change["x"], $change["y"], $change["state"]);
		}
	}
	public function setRule($r){
		if(gettype($r) == "object" && get_class($r) == "Rule")
			$this->rule = $r;
		else
			throw new Exception('Not a object of type Rule.');
	}
	public function getRule(){
		return $this->rule;
	}
}
?>