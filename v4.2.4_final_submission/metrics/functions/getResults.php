<?php
/**
 * Program to send all 50 queries to Google, Bing and Blekko, retrieve the results
 * and store the results in files for later processing
 * 
 * PHP version 5
 * 
 * @category PHP
 * @package  PHP_Metasearch_Engine
 * @author   Andrew Lamb <andrew.r.d.lamb@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://csserver.ucd.ie/~01421662/
 */
//Disabled as expensive and uses a lot of queries, particularly for Google
//********************************************************************************
exit("
    This option is disabled as all result sets have already been retrieved.
    
    </div>
    </div>
    </div>
    </div>
    <div id=\"push\"></div>
    
    <div class=\"footer\">    
    <div class=\"container\">
    <p class=\"text-center\">&copy; Andrew Lamb 2013<br />
    <a class=\"text-center\" href=\"mailto:Andrew.Lamb@ucdconnect.ie\">
    email</a><br />
    <a class=\"btn btn-inverse\" href=\"index.php\">Back To Goobling</a>
    </p>
    </div>
    </div>"
);
//********************************************************************************

ini_set('max_execution_time', 600);

//INCLUDE FUNCTIONS:
require 'metrics/functions/getBlekkoResults.php'; //get results from Blekko
require 'metrics/functions/getGoogleResults.php'; //get results from Google
require 'metrics/functions/getBingResults.php'; //get results from Bing
require 'metrics/functions/removeDuplicates.php'; //remove duplicate URLs
require 'metrics/functions/aggregateResults.php'; //aggregate the results
require 'metrics/functions/calcWeight.php'; //calculate the weight for each engine
require 'metrics/functions/applyWeight.php'; //apply the weight to the results
require 'metrics/functions/compareValues.php'; //function used by usort to sort array


//****************************************************************************
//QUERY LIST FROM FILE
//Get contents of query list into a string
$queryStr = file_get_contents('metrics/trec2012queries.txt');

//Convert string to an array
$queryArr = explode("\n", $queryStr);

//Add query number to each string in array
$queryNum = 151;
for ($i=0; $i<50; $i++) {
    $queryArr[$i] = $queryNum++ . "|" . $queryArr[$i];
}

//Put Query Number and Query into separate elements
foreach ($queryArr as &$value) {
    $value = explode("|", $value);
}
    
//Get Results for each Query
for ($i=0; $i<50; $i++) {
    $query = $queryArr[$i][1];
    
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
    
    $weights = array($query => 
        array(  "google" => $wsumGoogle,
                "bing" => $wsumBing,
                "blekko" => $wsumBlekko));
    
    writeToFile($weights, "metrics/weightedResults", $query." weights");
    
    //*********************************************
    //Write results to file
    writeToFile($aggregatedResults, "metrics/weightedResults", $query);

    sleep(5); //pause between queries
}
?>