<?php
class Rule{
	public $s;
	public $b;
	public function __construct($s_, $b_){
		if(gettype($s_) == "array" && gettype($b_) == "array"){
			$this->s = $s_;
			$this->b = $b_;
		}
		else
			throw new Exception('Arguments to Rule constructor must be of type array.');
	}
}
?>