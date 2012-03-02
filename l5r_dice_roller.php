<?php

class l5r_dice_roller {
    
    protected $roll, $keep, $rounds, $rawResults, $results, $mostRolled, $highestRolled, $lowestRolled;
    
    public function __construct($roll, $keep, $rounds = 1000) {  
        $this->roll = $roll;
        $this->keep = $keep;
        $this->rounds = $rounds;
        $this->rawResults = array();
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
    
    public function averageResult() {
        
        // take the average of all the result sets, round to nearest whole number
        return round(array_sum($this->rawResults) / count($this->rawResults));
        
    }
    
}
?>
