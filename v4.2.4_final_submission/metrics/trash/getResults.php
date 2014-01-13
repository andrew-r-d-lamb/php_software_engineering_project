<?php
exit("You have already gotten and stored results!!!");

ini_set('max_execution_time', 600);

//INCLUDE FUNCTIONS:
include 'metrics/functions/getBlekkoResults.php'; //get results from Blekko
include 'metrics/functions/getGoogleResults.php'; //get results from Google
include 'metrics/functions/getBingResults.php'; //get results from Bing
include 'metrics/functions/removeDuplicates.php'; //remove duplicate URLs
include 'metrics/functions/aggregateResults.php'; //aggregate the results (Borda-Fuse)
include 'metrics/functions/calcWeight.php'; //calculate the weight for each search engine
include 'metrics/functions/applyWeight.php'; //apply the weight to the results
include 'metrics/functions/compareValues.php'; //function used by usort to sort array
include 'metrics/functions/writeToFile.php';

//****************************************************************************
//QUERY LIST FROM FILE
//Get contents of query list into a string
$queryStr = file_get_contents('metrics/trec2012queries.txt');

//Convert string to an array
$queryArr = explode("\n", $queryStr);
//var_dump($queryArr);

//Add query number to each string in array
$queryNum = 151;
for ($i=0; $i<50; $i++) {
    $queryArr[$i] = $queryNum++ . "|" . $queryArr[$i];
}
//var_dump($queryArr);

//Put Query Number and Query into separate elements
foreach ($queryArr as &$value) {
    $value = explode("|", $value);
}
    
//Get Results for each Query
for ($i=0; $i<50; $i++) {
    $query = $queryArr[$i][1];
    
    echo "Query at element: " . $i . " " . $query . "<br />";
    
    //Arrays to store results
    $blekkoResultsOrig = array(); //original results (may contain duplicates)
    $googleResultsOrig = array(); //original results (may contain duplicates)
    $bingResultsOrig = array ();  //original results (may contain duplicates)
    $blekkoResults = array(); //results with duplicates removed
    $googleResults = array(); //results with duplicates removed
    $bingResults = array();   //results with duplicates removed
    $aggregatedResults = array(); //array to store aggregated results in
    $weights = array(); //array to store weights applied
    
    //CALL FUNCTIONS TO GET RESULTS FROM SEARCH ENGINES
    getBlekkoResults($blekkoResultsOrig, $query); //get Blekko
    getGoogleResults($googleResultsOrig, $query); //get Google
    getBingResults($bingResultsOrig, $query); //get Bing

    //CALL FUNCTIONS TO REMOVE DUPLICATE URLS AND RE-SCORE DOCUMENTS
    removeDuplicates($blekkoResultsOrig, $blekkoResults);
    removeDuplicates($googleResultsOrig, $googleResults);
    removeDuplicates($bingResultsOrig, $bingResults);

    //echo "<h2>Google Results</h2>";
    //displayResults($googleResults); //display Google
    //var_dump($googleResults);
    //echo "<h2>Bing Results</h2>";
    //displayResults($bingResults); //display Bing
    //var_dump($bingResults);
    //echo "<h2>Blekko Results</h2>";
    //displayResults($blekkoResults); //display Blekko
    //var_dump($blekkoResults);
    
    //*********************************************
    //Write results to files
    writeToFile($blekkoResults, "metrics/blekkoResults", $query);
    writeToFile($bingResults, "metrics/bingResults", $query);
    writeToFile($googleResults, "metrics/googleResults", $query);

    //AGGREGATE the Results using Borda-Fuse
    //NOTE THAT THE ORDER THE ARRAYS ARE SENT IN TO BE AGGREGATED MATTERS 
    //FOR ANY RESULTS THAT HAVE THE SAME COMBINED SCORE/RANK
    aggregateResults(
        $googleResults, $bingResults, 
        $blekkoResults, $aggregatedResults
    );

    //DISPLAY aggregated results:
    //echo "<h2>Combined Results</h2>";
    //displayResults($aggregatedResults); //display combined
    //var_dump($aggregatedResults);
    
    //*********************************************
    //Write results to file
    writeToFile($aggregatedResults, "metrics/aggregatedResults", $query);


    //initialize variables to rank search engines
    $wsumGoogle = $wsumBing = $wsumBlekko = 0.00; 
    //CALCULATE THE WEIGHT FOR EACH SEARCH ENGINE
    calcWeight(
        $googleResults, $bingResults, 
        $blekkoResults, $wsumGoogle, 
        $wsumBing, $wsumBlekko
    );
    //APPLY THE WEIGHT TO EACH OF THE SEARCH ENGINES RESULTS
    applyWeight($googleResults, $wsumGoogle);
    applyWeight($bingResults, $wsumBing);
    applyWeight($blekkoResults, $wsumBlekko);
    //AGGREGATE the Results using Borda-Fuse
    $aggregatedResults = array();
    aggregateResults(
        $googleResults, $bingResults, 
        $blekkoResults, $aggregatedResults
    );

    //DISPLAY weighted aggregated results:
    //echo "<h2>Weighted Results</h2>";
    //echo "<h3>Weight applied for query: "// . $_POST['query'] . "<br />";
    //echo "Google: " . $wsumGoogle 
    //    . "% Bing: " . $wsumBing 
    //    . "% Blekko: " . $wsumBlekko 
    //    . "%</h3>";
    
    $weights = array($query => 
        array(  "google" => $wsumGoogle,
                "bing" => $wsumBing,
                "blekko" => $wsumBlekko));
    //var_dump($weights);
    writeToFile($weights, "metrics/weightedResults", $query." weights");
    
    //displayResults($aggregatedResults); //display combined
    //var_dump($aggregatedResults);
    
    //*********************************************
    //Write results to file
    writeToFile($aggregatedResults, "metrics/weightedResults", $query);

    sleep(5);
}
?>