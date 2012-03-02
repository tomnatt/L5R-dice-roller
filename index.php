<?php

error_reporting(E_ALL);
ini_set('display_errors', True);

require_once("l5r_dice_roller.php");

$roll = 7;
$keep = 4;

$rolling = new l5r_dice_roller($roll, $keep, 10);

$rolling->roll();

//print_r($rolling->results());
echo json_encode($rolling->results());

?>
<!DOCTYPE html>

<html>
<head>
	<title>L5R Dice roller</title>

  <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="js/excanvas.js"></script><![endif]-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery.jqplot.min.js"></script>
    <!--<script type="text/javascript" src="js/plugins/jqplot.dateAxisRenderer.min.js"></script>
    <script type="text/javascript" src="js/plugins/jqplot.barRenderer.min.js"></script>
    <script type="text/javascript" src="js/plugins/jqplot.categoryAxisRenderer.min.js"></script>
    <script type="text/javascript" src="js/plugins/jqplot.cursor.min.js"></script>
    <script type="text/javascript" src="js/plugins/jqplot.highlighter.min.js"></script>
    <script type="text/javascript" src="js/plugins/jqplot.dragable.min.js"></script>
    <script type="text/javascript" src="js/plugins/jqplot.trendline.min.js"></script>-->
    <link rel="stylesheet" type="text/css" hrf="css/jquery.jqplot.min.css" />
   
</head>
<body>

<div id="results" style="height:400px; width:800px;"></div>

<p>Average result: <?php echo $rolling->averageResult(); ?></p>

<script>
$(document).ready(function () {

    $.jqplot.config.enablePlugins = true;
 
    var s1 = $.parseJSON(<?php echo json_encode(json_encode($rolling->results())); ?>);
    
    console.log(s1);
    console.log($.isArray(s1));
 
    // include a little space around the mix / max for a more pleasing graph
    plot1 = $.jqplot('results',[s1],{
        title: 'Results',
        axes: {
            xaxis: {
              min: <?php echo $rolling->lowestRolled(); ?> - 5,
              max: <?php echo $rolling->highestRolled(); ?> + 5,  
                
            },
            yaxis: {
                min: 0,
                max: <?php echo $rolling->mostRolled(); ?> + 2
            }
        },
        cursor: {
            show: true
        }
    });
});
</script>

</body>
</html>
