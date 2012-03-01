<?php

$rolled = 4;
$kept = 2;

function rollD10() {
	$result = rand(1, 10);
	if ($result == 10) {
		$result = $result + rollD10();
	}
	return $result;
}

// generate every result
$results = array();

for ($i = 0; $i < $rolled; $i++) {
	$results[] = rollD10();
}

// sort them
rsort($results, SORT_NUMERIC);

// sum the highest kept
$total = 0;
for ($i = 0; $i < $kept; $i++) {
	$total = $total + $results[$i];
} 
print_r($results);
echo $total;

?>
