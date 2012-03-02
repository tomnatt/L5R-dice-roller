<?php

error_reporting(E_ALL);
ini_set('display_errors', True);

require_once("l5r_dice_roller.php");

$roll = $_GET["roll"];
$keep = $_GET["keep"];
$rounds = 1000;

$rolling = new l5r_dice_roller($roll, $keep, $rounds);
$rolling->roll();

?>
<!DOCTYPE html>

<html>
<head>
	<title>L5R Dice roller</title>

  <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="js/excanvas.js"></script><![endif]-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/jquery.jqplot.min.js"></script>
    <script type="text/javascript" src="js/plugins/jqplot.barRenderer.min.js"></script>
    <script type="text/javascript" src="js/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
    <script type="text/javascript" src="js/plugins/jqplot.canvasTextRenderer.min.js"></script>
    <script type="text/javascript" src="js/plugins/jqplot.cursor.min.js"></script>
    <script type="text/javascript" src="js/plugins/jqplot.highlighter.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/jquery.jqplot.min.css" />
    <link rel="stylesheet" type="text/css" href="css/dice.css" />
   
</head>
<body>

<h1>L5R dice statistics generator</h1>

<!-- form -->
<form action="." method="get">
    <label>
        Roll: 
        <input type="text" name="roll" id="roll" />
    </label>
    <label>
        Keep:
        <input type="text" name="keep" id="keep" />
    </label>
    <input type="submit" value="Roll those dice!" />
</form>


<!-- results -->
<p>Roll: <?php echo $roll;?>, keep: <?php echo $keep; ?> - any over 10 observe the ten dice rule</p>
<p>Rolled <?php echo $rounds; ?> times</p>

<div id="results" style="height:400px; width:800px;"></div>

<p>Average result: <strong><?php echo $rolling->averageResult(); ?></strong></p>

<script>
$(document).ready(function () {

    $.jqplot.config.enablePlugins = true;
 
    var s1 = $.parseJSON(<?php echo json_encode(json_encode($rolling->results())); ?>);
    
    //console.log(s1);
    //console.log($.isArray(s1));
 
    // include a little space around the mix / max for a more pleasing graph
    plot1 = $.jqplot('results',[s1],{
        axes: {
            xaxis: {
                label: "Dice result",
                min: <?php echo $rolling->lowestRolled(); ?> - 5,
                max: <?php echo $rolling->highestRolled(); ?> + 5,  
            },
            yaxis: {
                label: "# rolled",
                min: 0,
                max: <?php echo $rolling->mostRolled(); ?> + 2
            }
        },
        cursor: {
            show: true
        },
        seriesDefaults: {
          rendererOptions: {
              smooth: true
          }
        },
        axesDefaults: {
            labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
            tickOptions: {
               formatString: '%d'
            }
        }, 
    });
});
</script>

</body>
</html>
