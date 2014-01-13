<?php
/**
 * Program to: 
 * display metrics, 
 * recalculate metrics based on current set
 * get new set of results and calculate metrics on those
 * 
 * PHP version 5
 * 
 * @category PHP
 * @package  PHP_Metasearch_Engine
 * @author   Andrew Lamb <andrew.r.d.lamb@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://csserver.ucd.ie/~01421662/
 */
//Turn off error reporting
error_reporting(0);

//Include the header
require 'includes/metricsHeader.php';

if (isset($_POST['theMetrics'])) {
    if ($_POST['metrics'] === 'metricsCalc') {
        include 'metrics/functions/writeToFile.php'; //enable writing to file
        
        //read results from file, 
        //recalculate metrics 
        //and display
        include 'metrics/functions/evaluate.php';
        
    } else if ($_POST['metrics'] === 'metricsGet') {
        include 'metrics/functions/writeToFile.php'; //enable writing to file

        //get new set of results and store in files
        include 'metrics/functions/getResults.php';
        
        //read results from file, 
        //recalculate metrics 
        //and display
        include 'metrics/functions/evaluate.php';   
        
    } else {
        include 'metrics/functions/readFromFile.php'; //enable reading from file

        //read metrics from file 
        $mainMetrics = array(); //array to populate with main metrics scores
        $mainMetricsLocation = "metrics/evaluationScores";
        $mainMetricsFilename = "mainMetrics.txt";
        $mapScores = array(); //array to populate with map scores
        $mapLocation = "metrics/evaluationScores";
        $mapFilename = "mapScores.txt";
        readFromFile($mainMetrics, $mainMetricsLocation, $mainMetricsFilename);
        readFromFile($mapScores, $mapLocation, $mapFilename);
        
        //and display
        displayMetrics($mainMetrics, $mapScores);
    }  
}

/**
 * Function takes in metrics and map scores arrays, and displays contents
 * 
 * @param array $mainMetrics array to display
 * @param array $mapScores   array to display
 * 
 * @return void
 */
function displayMetrics($mainMetrics, $mapScores) 
{
    $i=0; //counter for mapScores

    //display metrics
    foreach ($mainMetrics as $key => $value) {    
        echo "<div class=\"row-fluid\">"
        . "<div class=\"span12\">";            
        echo "<h2>" . $key . "</h2>";
        echo "<h3>Mean Average Precision: " . $mapScores[$i++][1] . "</h3>";
        echo "</div></div>";
        echo "<div class=\"row-fluid\">"
            . "<div class=\"span3\">";
        echo "</div>";
        echo "<div class=\"span9\">";
        echo "<p style=\"text-align:justify\">";
        echo "<strong> qNo &nbsp p3 &nbsp &nbsp &nbsp p5 &nbsp &nbsp &nbsp &nbsp p10 
            &nbsp &nbsp p20 &nbsp &nbsp p25 &nbsp &nbsp &nbsp p50 &nbsp &nbsp pMx 
            &nbsp &nbsp rMx &nbsp &nbsp avP &nbsp &nbsp &nbsp f-M &nbsp &nbsp tDcs 
            &nbsp query  </strong><br />";
        foreach ($mainMetrics[$key] as $subKey => $subValue) {
            echo "$subValue[0] &nbsp " 
                . number_format($subValue[2], 3, '.', '') . " &nbsp "
                . number_format($subValue[3], 3, '.', '') . " &nbsp " 
                . number_format($subValue[4], 3, '.', '') . " &nbsp " 
                . number_format($subValue[5], 3, '.', '') . " &nbsp "
                . number_format($subValue[6], 3, '.', '') . " &nbsp "
                . number_format($subValue[7], 3, '.', '') . " &nbsp "
                . number_format($subValue[8], 3, '.', '') . " &nbsp " 
                . number_format($subValue[11], 3, '.', '') . " &nbsp "             
                . number_format($subValue[9], 3, '.', '') . " &nbsp "
                . number_format($subValue[10], 3, '.', '') . " &nbsp "
                . sprintf("%03s", $subValue[12]) . " &nbsp "
                . $subValue[1] . " "          
                . "<br />";
        }  
        echo "<a href=\"#top\" styel=\"text-align:center\">
            <small>back to top of page</small></a></p>";
        echo "</div>";
        echo "</div>";
    }
}

//include the footer
require 'includes/metricsFooter.php';
?>