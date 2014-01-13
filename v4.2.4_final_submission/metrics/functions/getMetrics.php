<?php
/**
 * Function to calculate the Metrics Scores for each individual query
 * 
 * PHP version 5
 * 
 * @category PHP
 * @package  PHP_Metasearch_Engine
 * @author   Andrew Lamb <andrew.r.d.lamb@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://csserver.ucd.ie/~01421662/
 */
/**
 * Function takes in a number of parameters and calculates the metrics scores
 * 
 * @param array  &$outputArray      array to populate with metrics scores
 * @param array  $goldStandardArray gold standard array to compare against
 * @param string $engineName        the name of the engine/aggregation method
 * @param string $queryName         the name of the query, e.g., 403b
 * @param string $queryNum          the query number, e.g., 151
 * @param array  $resultsArray      the array of results to check
 * 
 * @return void
 */
function getMetrics(&$outputArray, $goldStandardArray
    , $engineName, $queryName
    , $queryNum, $resultsArray
) {
    $maxCount = count($resultsArray); //get a count of number of results in array
    $foundCount = 0; //initialize variable to store number of URLs found
    
    //initialize variables to store individual metrics
    $p3 = $p5 = $p10 = $p15 = $p20 = ${'p'.$maxCount} = $avgP = $fMeasure = 0;
    
    //loop through results array
    for ($i=0; $i<$maxCount; $i++) {
        //loop through gold standards
        //if found add 1 to found count
        foreach ($goldStandardArray as $key => $value) {
            if ($resultsArray[$i][0] === $value[1]) {
                $foundCount++;
                break 1;
            }
        }
        
        //Store precision at N values: 3, 5, 10, 20, 25, 50, and last
        if ($i===2) {
            $p3 = round($foundCount/3, 3);
        }
        if ($i===4) {
            $p5 = round($foundCount/5, 3);
        }
        if ($i===9) {
            $p10 = round($foundCount/10, 3);
        }
        if ($i===14) {
            $p15 = round($foundCount/15, 3);
        }
        if ($i===19) {
            $p20 = round($foundCount/20, 3);
        }
        if ($i===24) {
            $p25 = round($foundCount/25, 3);
        }
        if ($i===49) {
            $p50 = round($foundCount/50, 3);
        }
        if ($i===$maxCount-1) {
            ${'p'.$maxCount} = round($foundCount/$maxCount, 3);
            $docsReturned = $maxCount; //the total number of docs returned
        }
    }
    
    //Calculate Average Precision
    //Add the calculated precision values together and divide by gold standard
    $avgP = ($p3 + $p5 + $p10 + $p20 + $p25 + $p50)/100;
    
    //Calculate F-Measure
    //(2*P*R) / (P+R)
    $precision = ${'p'.$maxCount};
    $recall = $foundCount / 100; //final recall found/gold standard
    $fMeasure = (2 * $precision * $recall) / ($precision + $recall);
    
    //Add calculations to array
    $outputArray[$engineName][] = array(
        $queryNum, $queryName, 
        $p3, $p5, $p10, $p20, $p25, $p50, ${'p'.$maxCount}, 
        $avgP, $fMeasure, $recall, $docsReturned);
}
?>