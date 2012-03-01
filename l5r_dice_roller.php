<?php

class l5r_dice_roller {
    
    protected $roll, $keep, $rounds, $results;
    
    public function __construct($roll, $keep, $rounds = 1000) {  
        $this->roll = $roll;
        $this->keep = $keep;
        $this->rounds = $rounds;
        $this->results = array();
    }
    
    function rollSingleD10() {
        $result = rand(1, 10);
        if ($result == 10) {
            $result = $result + $this->rollSingleD10();
        }
        return $result;
    }
  
    
    public function rollOneSet() {

        $set = array();

        for ($i = 0; $i < $this->roll; $i++) {
            $set[] = $this->rollSingleD10();
        }
        // sort them
        rsort($set, SORT_NUMERIC);
        return $set;
    }
    
    public function singleResult() {
    
        $set = $this->rollOneSet();
        // sum the highest kept
        $total = 0;
        for ($i = 0; $i < $this->keep; $i++) {
            $total = $total + $set[$i];
        }         
        return $total;
    }
    
    public function roll() {
        
        // roll your xKy dice $rounds times so we can get an average 
        for ($i = 0; $i < $this->rounds; $i++) {
            $this->results[] = $this->singleResult();
        }
        
    }
    
    public function result() {
        
        // take the average of all the result sets, round to nearest whole number
        return round(array_sum($this->results) / count($this->results));
        
    }
    
}
?>
