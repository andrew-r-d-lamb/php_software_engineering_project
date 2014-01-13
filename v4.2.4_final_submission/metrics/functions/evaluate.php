<?php
/**
 * Main Metrics Engine
 * 
 * PHP version 5
 * 
 * @category PHP
 * @package  PHP_Metasearch_Engine
 * @author   Andrew Lamb <andrew.r.d.lamb@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://csserver.ucd.ie/~01421662/
 */
ini_set('max_execution_time', 600);
require 'metrics/functions/readFromFile.php';
require 'metrics/functions/getMetrics.php';
require 'metrics/functions/getMaps.php';
//require 'metrics/functions/writeToFile.php';
require 'metrics/functions/writeToCSV.php';

//********************************************************************************
//GOLD STANDARDS FROM FILE
//Get contents of Gold Standards file into a string
$goldStandardStr = file_get_contents('metrics/relevancejudgments.txt');

//Convert string to array
$goldStandardArr = explode("\n", $goldStandardStr);

//Put number and url into separate elements
foreach ($goldStandardArr as $key => &$value) {
    $value = explode(" ", $value);
}

//Regular Expressions to be replaced in url:
$urlItemsSearchFor = array(
    '/(http|https|ftp|sftp)\:\/\//', //http|https|ftp|sftp://
    '/(www|www2)\./', //www|www2.
    '{/$}'); //trailing forward slash
$urlItemsReplaceWith = ''; //Replace with ''

//Trim URLs using above regular expression
foreach ($goldStandardArr as $key => &$value) { 
    $value[1] = preg_replace($urlItemsSearchFor, $urlItemsReplaceWith, $value[1]);
}
//Gold Standards Now Look Like This
//array ([0] => array ( [0] => 151 
//                      [1] => 403bwise.com
//                      ))                      
//********************************************************************************

//********************************************************************************
//QUERY LIST FROM FILE
//Get contents of query list into a string
$queryStr = file_get_contents('metrics/trec2012queries.txt');

//Convert string to an array
$queryArr = explode("\n", $queryStr);

//Add query number to each string in array
$queryNum = 151;
for ($i=0; $i < 50; $i++) {
    $queryArr[$i] = $queryNum++ . "|" . $queryArr[$i];
}

//Put Query Number and Query into separate elements
foreach ($queryArr as &$value) {
    $value = explode("|", $value);
}
//Query Array Now Looks Like This
//array ([0] => array ( [0] => 151
//                      [1] => 403b
//                      ))
//********************************************************************************

//********************************************************************************
//GET INDIVIDUAL RESULTS FROM FILES AND CALCULATE METRICS
//Set the names for the directories
$bingDirectory = "metrics/bingResults";
$blekkoDirectory = "metrics/blekkoResults";
$googleDirectory = "metrics/googleResults";
$aggregatedDirectory = "metrics/aggregatedResults";
$weightedDirectory = "metrics/weightedResults";

//Initialize arrays to store calculated metrics
$mainMetrics = array();
$mapScores = array();

//Initialize Arrays and read individual results into them
//Trimmed URL to check will be found at array[][0]
//Call function to calculate individual metrics at each stage
for ($i=151; $i<=200; $i++) {
    $queryNumber = $i;
    $query = $queryArr[$i-151][1];
    $fileName = $query.".txt";
    
    ${'bing'.$i} = array();
    readFromFile(${'bing'.$i}, $bingDirectory, $fileName);
    //getMetrics
    getMetrics(
        $mainMetrics, $goldStandardArr, "bing", 
        $query, $queryNumber, ${'bing'.$i}
    );
    
    ${'blekko'.$i} = array();
    readFromFile(${'blekko'.$i}, $blekkoDirectory, $fileName);
    //getMetrics
    getMetrics(
        $mainMetrics, $goldStandardArr, "blekko", 
        $query, $queryNumber, ${'blekko'.$i}
    );
    
    ${'google'.$i} = array();
    readFromFile(${'google'.$i}, $googleDirectory, $fileName);
    //getMetrics
    getMetrics(
        $mainMetrics, $goldStandardArr, "google", 
        $query, $queryNumber, ${'google'.$i}
    );
    
    ${'aggregated'.$i} = array();
    readFromFile(${'aggregated'.$i}, $aggregatedDirectory, $fileName);
    //getMetrics
    getMetrics(
        $mainMetrics, $goldStandardArr, "aggregated", 
        $query, $queryNumber, ${'aggregated'.$i}
    );
    
    ${'weighted'.$i} = array();
    readFromFile(${'weighted'.$i}, $weightedDirectory, $fileName);
    //getMetrics
    getMetrics(
        $mainMetrics, $goldStandardArr, "weighted", 
        $query, $queryNumber, ${'weighted'.$i}
    );
}

//Call Function to calculate the Mean Average Precision
getMaps($mapScores, $mainMetrics);

//DISPLAY THE RESULTS
displayMetrics($mainMetrics, $mapScores); //function in metrics.php

//WRITE THE METRICS SCORES TO FILES
writeToFile($mainMetrics, "metrics/evaluationScores", "mainMetrics");
writeToFile($mapScores, "metrics/evaluationScores", "mapScores");

//WRITE THE METRICS TO CSV FILE
writeToCSV($mainMetrics, $mapScores, "metrics/evaluationScores", "combinedMetrics");
?>