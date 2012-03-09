<?php

error_reporting(E_ALL);
ini_set('display_errors', True);

require_once("l5r_dice_roller.php");

$roll = "";
if (isset($_GET["roll"])) {
    $roll = $_GET["roll"];
}

$keep = "";
if (isset($_GET["keep"])) {
    $keep = $_GET["keep"];
}

$emphasis = false;
if (isset($_GET["emphasis"]) && $_GET["emphasis"] == "on") {
    $emphasis = true;
}
$fixed = false;
if (isset($_GET["fixed"]) && $_GET["fixed"] == "on") {
    $fixed = true;
}
$rounds = 1000;

$rolling = new l5r_dice_roller($roll, $keep, $emphasis, $rounds);
$rolling->roll();

?>
<!DOCTYPE html>

<html>
<head>
    <title>L5R Dice roller</title>
    <meta name="description" content="Roll dice and generate statistics for L5R system" />
    <meta name="keywords" content="L5R, Legend of the Five Rings, dice roller, statistics" />
    <meta name="author" content="Tom Natt" />

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
        <input type="text" name="roll" id="roll" value="<?php echo $roll; ?>" />
    </label>
    <label>
        Keep:
        <input type="text" name="keep" id="keep" value="<?php echo $keep; ?>" />
    </label>
    <label>
        Emphasis?
        <input type="checkbox" name="emphasis" id="emphasis" <?php if ($emphasis) { ?>checked="checked"<?php } ?> />
    </label>
    <label>
        Fix graph axis?
        <input type="checkbox" name="fixed" id="fixed" <?php if ($fixed) { ?>checked="checked"<?php } ?> />
    </label>
    <input type="submit" value="Roll those dice!" />
</form>


<!-- results -->
<h2>Results</h2>

<div id="results" style="height:400px; width:800px;"></div>

<p>Average result: <strong><?php echo $rolling->averageResult(); ?></strong></p>
<p>Roll: <?php echo $roll;?>, keep: <?php echo $keep; ?> - any over 10 observe the ten dice rule</p>
<?php if ($emphasis) { ?><p>Rerolled 1s (as per skill emphasis)</p><?php } ?>
<p>Rolled <?php echo $rounds; ?> times</p>

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
                min: <?php echo ($fixed ? 0 : $rolling->lowestRolled() - 5); ?>,
                max: <?php echo ($fixed ? 100 : $rolling->highestRolled() + 5); ?>,  
            },
            yaxis: {
                label: "# rolled",
                min: 0,
                max: <?php echo ($fixed ? 120 : $rolling->mostRolled() + 2); ?>,
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
