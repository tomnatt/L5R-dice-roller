<?php

class l5r_dice_roller {
    
    protected $roll, $keep, $modifier, $emphasis, $rounds, $rawResults, $results, $mostRolled, $highestRolled, $lowestRolled, $canRoll;
    
    public function __construct($roll, $keep, $emphasis = false, $rounds = 1000) {
    
        // ensure we have sane input for roll and keep
        if (preg_match('/^\d+$/', $roll) && $roll > 0) {  
            $this->roll = $roll;
        } else {
            $this->roll = 0;
        }
        if (preg_match('/^\d+$/', $keep) && $keep > 0) {  
            $this->keep = $keep;
        } else {
            $this->keep = 0;
        }
        
        $this->modifier = 0;
        $this->emphasis = $emphasis;
        $this->rounds = $rounds;
        $this->rawResults = array();
        $this->results = array();
        
        // l5r 4e p77
        $this->tenDiceRule();
        
        // remember you can't keep more than you roll!
        if ($this->keep > $this->roll) {
            $this->keep = $this->roll;
        }
    }
    
    private function tenDiceRule() {
    
        // if $roll exceeds 10, add 1 kept dice for every 2 excess roll
        if ($this->roll > 10) {
            $this->keep = $this->keep + floor(($this->roll - 10) / 2);
            $this->roll = 10;
        }
        
        // if $keep exceeds 10 add 2 to the total for every excess keep
        if ($this->keep > 10) {
            $this->modifier = ($this->keep - 10) * 2;
            $this->keep = 10;
        }
        
    }
    
    public function rollSingleD10() {
        $result = rand(1, 10);
        
        // if we have an emphasis, reroll 1s once
        if ($this->emphasis && $result == 1) {
            $result = rand(1, 10);
        }
        
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
        
        // add on the modifier
        $total = $total + $this->modifier;
                 
        return $total;
    }
    
    public function roll() {
        
        // roll your xKy dice $rounds times so we can get an average 
        for ($i = 0; $i < $this->rounds; $i++) {
            $this->rawResults[] = $this->singleResult();
        }
        
        // sort for easier use
        sort($this->rawResults, SORT_NUMERIC);
        
        $this->lowestRolled = $this->rawResults[0];
        $this->highestRolled = $this->rawResults[count($this->rawResults) - 1];
   
        // create multidimensional array of result => occurances, removing dupes
        $used = array();
        foreach ($this->rawResults as $r) {
            if (!in_array($r, $used)) {
            
                // store the greatest instances of a result rolled
                $timesRolled = count(array_keys($this->rawResults, $r));
                if ($timesRolled > $this->mostRolled) {
                    $this->mostRolled = $timesRolled;
                }
            
                $this->results[] = array("$r", $timesRolled);
                $used[] = $r;
            }
        }
        
    }
    
    public function mostRolled() {
        return $this->mostRolled;        
    }
    
    public function highestRolled() {
        return $this->highestRolled;        
    }
    
    public function lowestRolled() {
        return $this->lowestRolled;        
    }
    
    public function results() {
        return $this->results;        
    }
    
    public function rawResults() {
        return $this->rawResults;        
    }
    
    public function averageResult() {
        
        // take the average of all the result sets, round to nearest whole number
        return round(array_sum($this->rawResults) / count($this->rawResults));
        
    }
    
}
?>
