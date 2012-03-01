<?php

error_reporting(E_ALL);
ini_set('display_errors', True);

require_once("l5r_dice_roller.php");

$roll = 7;
$keep = 4;

$rolling = new rollD10($roll, $keep);

$rolling->roll();
echo $rolling->result();

?>
