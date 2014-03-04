<?php
require('arraysearch.php');
class LifeServer{
	private $sizex;
	private $connection;
	private $db;
	private $collection;
	public function __construct($sx, $sy){
		$this->sizex = $sx;
		$this->sizey = $sy;
		$this->connection = new MongoClient();
		$this->collection = $this->connection->selectCollection('lifemmo', 'cells');
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
				if($nsum == 3){
					echo "nsum: $nsum\n";
					echo "thisdata: $thisdata\n";
				}
				$thisdata = searchSubArray($data, "x", $i, "y", $j);
				$thisdata = $thisdata["state"];
				if($nsum < 2 && $thisdata == 1){
					$changes[] = array("x" => $i,
					"y" => $j,
					"state" => 0);
				}
				else if(($nsum == 2 || $nsum == 3) && $thisdata == 1){
					continue;
				}
				else if($nsum > 3 && $thisdata == 1){
					$changes[] = array("x" => $i,
					"y" => $j,
					"state" => 0);
				}
				else if($nsum == 3 && $thisdata == 0){
					$changes[] = array("x" => $i,
					"y" => $j,
					"state" => 1);
				}
			}
		}
		var_dump($changes);
		//step two: apply changes
		foreach($changes as $change){
			$this->draw($change["x"], $change["y"], $change["state"]);
		}
	}
}
?>