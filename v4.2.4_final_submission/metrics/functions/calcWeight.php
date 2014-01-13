<?php
/**
 * Function to calculate weights for Google, Bing and Blekko's Results
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
 * Function takes in 3 arrays by value, and three floats by reference
 * caluclates the weights to be applied to each array / result-set by
 * Using the wsum technique which weights results based on there combined score
 * for the number of results they have that appear in the other sets
 * 
 * @param array $set1      first set of results
 * @param array $set2      second set of results
 * @param array $set3      third set of results
 * @param float &$wsumSet1 the weight to be calculated for set1
 * @param float &$wsumSet2 the weight to be calculated for set2
 * @param float &$wsumSet3 the weight to be calculated for set3
 * 
 * @return void
 */
function calcWeight($set1, $set2, $set3, &$wsumSet1, &$wsumSet2, &$wsumSet3)
{
    //compare each element of first result set with second result set,
    //  if same result found, add one to the score for both result sets.  
    foreach ($set1 as $set1Key => &$set1Value) {
        foreach ($set2 as $set2Key => &$set2Value) {
            if ($set1Value[0] === $set2Value[0]) {
                $wsumSet1++;
                $wsumSet2++;
            }
        }
        //compare each element of first result set with third result set,
        //if same result found, add one to the score for both result sets.
        foreach ($set3 as $set3Key => &$set3Value) {
            if ($set1Value[0] === $set3Value[0]) {
                $wsumSet1++;
                $wsumSet3++;
            }
        }
    }
    //Compare each element of second result set with third result set
    //if same result found, add one to the score for both result sets.
    foreach ($set2 as $set2Key => &$set2Value) {
        foreach ($set3 as $set3Key => &$set3Value) {
            if ($set2Value[0] === $set3Value[0]) {
                $wsumSet2++;
                $wsumSet3++;
            }
        }
    }
    
    //Calculate highest possible score for weight.  
    //As comparing with 2 other result sets, highest score is 2 * number of results.
    $maxWeightPossible = 100 * 2;
  
    //Calculate weighted sum amount for each result set as a 
    //percentage of maximum weight possible
    //round rounds the value of a percentage 
    //to x number of digits (based on the number after the comma)
    $wsumSet1 = round(($wsumSet1 / $maxWeightPossible) * 100, 2); 
    $wsumSet2 = round(($wsumSet2 / $maxWeightPossible) * 100, 2); 
    $wsumSet3 = round(($wsumSet3 / $maxWeightPossible) * 100, 2); 
    
    //reduce wsum amounts if no results found
    if ($set1[0][5] === "NULL") {
        $wsumSet1 = 0.000001;
    }
    if ($set2[0][5] === "NULL") {
        $wsumSet2 = 0.000001;
    }
    if ($set3[0][5] === "NULL") {
        $wsumSet3 = 0.000001;
    }
    
    //increase to 1 to avoid dividing by 0 later on
    if ($wsumSet1 < 1) {
        $wsumSet1 = 1;
    }
    if ($wsumSet2 < 1) {
        $wsumSet2 = 1;
    }
    if ($wsumSet3 < 1) {
        $wsumSet3 = 1;
    }
}
?>
