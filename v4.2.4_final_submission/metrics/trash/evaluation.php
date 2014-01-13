<?php
ini_set('max_execution_time', 600);
require 'functions/readFromFile.php';
require 'functions/getMetrics.php';
require 'functions/getMaps.php';
require 'functions/writeToFile.php';
require 'functions/writeToCSV.php';

//********************************************************************************
//GOLD STANDARDS FROM FILE
//Get contents of Gold Standards file into a string
$goldStandardStr = file_get_contents('relevancejudgments.txt');

//Convert string to array
$goldStandardArr = explode("\n", $goldStandardStr);
//print_r($goldStandardArr);

//Put number and url into separate elements
foreach ($goldStandardArr as $key => &$value){
    $value = explode(" ", $value);
}
//print_r($goldStandardArr);

//Regular Expressions to be replaced in url:
$urlItemsSearchFor = array(
    '/(http|https|ftp|sftp)\:\/\//', //http|https|ftp|sftp://
    '/(www|www2)\./', //www|www2.
    '{/$}'); //trailing forward slash
$urlItemsReplaceWith = ''; //Replace with ''

//Trim URLs using above regular expression
foreach ($goldStandardArr as $key => &$value) {
    //echo $key . " " . $value[1] . "<br />";   
    $value[1] = preg_replace($urlItemsSearchFor, $urlItemsReplaceWith, $value[1]);
}
//print_r($goldStandardArr);

//Gold Standards Now Look Like This
//array ([0] => array ( [0] => 151 
//                      [1] => 403bwise.com
//                      ))                      
//********************************************************************************

//********************************************************************************
//QUERY LIST FROM FILE
//Get contents of query list into a string
$queryStr = file_get_contents('trec2012queries.txt');

//Convert string to an array
$queryArr = explode("\n", $queryStr);
//var_dump($queryArr);

//Add query number to each string in array
$queryNum = 151;
for ($i=0; $i < 50; $i++) {
    $queryArr[$i] = $queryNum++ . "|" . $queryArr[$i];
}
//var_dump($queryArr);

//Put Query Number and Query into separate elements
foreach ($queryArr as &$value) {
    $value = explode("|", $value);
}
//var_dump($queryArr);
/*
for ($i=0; $i<50; $i++) {
    //$query = $value[1];
    echo $queryArr[$i][1] . "<br />";
}
*/
//Query Array Now Looks Like This
//array ([0] => array ( [0] => 151
//                      [1] => 403b
//                      ))
//********************************************************************************

//********************************************************************************
//GET INDIVIDUAL RESULTS FROM FILES AND CALCULATE METRICS
//Set the names for the directories
$bingDirectory = "bingResults";
$blekkoDirectory = "blekkoResults";
$googleDirectory = "googleResults";
$aggregatedDirectory = "aggregatedResults";
$weightedDirectory = "weightedResults";

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
    getMetrics($mainMetrics, $goldStandardArr, "bing", $query, $queryNumber, ${'bing'.$i});
    
    ${'blekko'.$i} = array();
    readFromFile(${'blekko'.$i}, $blekkoDirectory, $fileName);
    //getMetrics
    getMetrics($mainMetrics, $goldStandardArr, "blekko", $query, $queryNumber, ${'blekko'.$i});
    
    ${'google'.$i} = array();
    readFromFile(${'google'.$i}, $googleDirectory, $fileName);
    //getMetrics
    getMetrics($mainMetrics, $goldStandardArr, "google", $query, $queryNumber, ${'google'.$i});
    
    ${'aggregated'.$i} = array();
    readFromFile(${'aggregated'.$i}, $aggregatedDirectory, $fileName);
    //getMetrics
    getMetrics($mainMetrics, $goldStandardArr, "aggregated", $query, $queryNumber, ${'aggregated'.$i});
    
    ${'weighted'.$i} = array();
    readFromFile(${'weighted'.$i}, $weightedDirectory, $fileName);
    //getMetrics
    getMetrics($mainMetrics, $goldStandardArr, "weighted", $query, $queryNumber, ${'weighted'.$i});
}

//Call Function to calculate the Mean Average Precision
getMaps($mapScores, $mainMetrics);

//DISPLAY THE RESULTS
//number_format($value[3], 2, '.', '')
//$outputArray[$engineName][] = array(
//      (0)$queryNum, (1)$queryName, 
//      (2)$p3, (3)$p5, (4)$p10, (5)$p20, (6)$p25, (7)$p50, (8)${'p'.$maxCount}, 
//      (9)$avgP, (10)$fMeasure, (11)$recall, (12)$docsReturned);
$i = 0;
foreach ($mainMetrics as $key => $value) {    
    echo "<h2>" . $key . "</h2>";
    echo "<h3>Mean Average Precision: " . $mapScores[$i++][1] . "</h3><br />";
    echo "<strong> qNo &nbsp p3 &nbsp &nbsp &nbsp p5 &nbsp &nbsp &nbsp &nbsp p10 
        &nbsp &nbsp p20 &nbsp &nbsp p25 &nbsp &nbsp &nbsp p50 &nbsp &nbsp pMx 
        &nbsp &nbsp rMx &nbsp &nbsp avP &nbsp &nbsp f-M &nbsp tDcs &nbsp query  </strong><br />";
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
            //. number_format($subValue[12], 0, '.', '') . " &nbsp "
            . $subValue[1] . " "          
            . "<br />";
    }
    //$i++;
//echo $mainMetrics[$key][0][0];
}

//WRITE THE METRICS SCORES TO FILES
writeToFile($mainMetrics, "evaluationScores", "mainMetrics");
writeToFile($mapScores, "evaluationScores", "mapScores");

//WRITE THE METRICS TO CSV FILE
writeToCSV($mainMetrics, $mapScores, "evaluationScores", "combinedMetrics");


//!!! Precision and Recall are often inversely related - one increases the other decreases
//PRECISION FORMULA
//number of relevant documents retrieved / total number of (unique?) documents retrieved
// precision = [relevant documents] intersection [retrieved documents] divided by [retrieved documents]



//RECALL FORMULA
//number of relevant documents retrieved / total number of relevant documents in collection
// recall = [relevant documents] intersection [retrieved documents] divided by [relevant documents]
//!!!

//PRECISION @ 10 FORMULA
//precision at 10 is number or relevant results in the top 10 documents / 10

//MAP (MEAN AVERAGE PRECISION) FORMULA
//CALCULATE AVERAGE PRECISION FOR EACH QUERY
    //AVERAGE PRECISION is measuring precision at each recall point. Set recall points to e.g., 1, 3, 5, 10, 25, etc
        //get precision at each of these using PRECISION @ N FORMULA
        //(number or relevant results in the top N documents / N)
        //sum these totals and divide by the total number of relevant documents to get Average Precision
//CALCULATE THE AVERAGE OF THE SCORES RETURNED BY AVERAGE PRECISION
 
//F-MEASURE FORMULA
//F-MEASURE = (2 * PRECISION * RECALL) / PRECISION + RECALL



?>