<?php
/**
 * Function to aggregate the results from Google, Bing and Blekko
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
 * Function takes in 4 arrays by reference, compares the first three and
 * aggregates them into the fourth aggregated set
 * 
 * @param array &$set1          first set of results
 * @param array &$set2          second set of results
 * @param array &$set3          third set of results
 * @param array &$aggregatedSet aggregated set of results
 * 
 * @return void
 */
function aggregateResults(&$set1, &$set2, &$set3, &$aggregatedSet) 
{
    //******************************************************************************
    //COMPARE 1ST RESULT SET WITH OTHER 2 RESULT SETS.  STEPS:
    //  LOOP THROUGH FIRST RESULT SET
    //    CREATE ENTRY IN AGGREGATED RESULT SET FOR EACH ENTRY
    //      COMPARE ENTRY TO 2ND RESULT SET
    //      IF URL FOUND/SEEN - 
    //        SUM TOTAL IN AGGREGATED SET
    //        MARK AS SEEN IN 2ND RESULT SET
    //      COMPARE ENTRY TO 3RD RESULT SET
    //      IF URL FOUND/SEEN -
    //        SUM TOTAL IN AGGREGATED SET
    //        MARK AS SEEN IN 3RD RESULT SET
  
    $k = 0; //incremental variable to control element of new aggregated array.

    //value[0] is trimmed URL, 
    //value[1] is URL Title, value[2] is Snippet, 
    //value[3] is Rank/Score, value[4] is y/n switch for seen urls, 
    //value[5] is original url, 
    //value[6] is empty string to populate with cleaned snippet
    //"" is an additional placeholder for storing coordinate
    foreach ($set1 as $set1Key => &$set1Value) {
        $aggregatedSet[$k] = (
            array($set1Value[0], $set1Value[1], 
                $set1Value[2], $set1Value[3], 
                $set1Value[5], $set1Value[6], "")); 
                //add each set1Result to aggregated Array

        foreach ($set2 as $set2Key => &$set2Value) {
            if ($set1Value[0] === $set2Value[0]) {
                $aggregatedSet[$k][3] = $aggregatedSet[$k][3] + $set2Value[3];
                $set2Value[4] = 'y';
                break 1;
            }
        }
        
        foreach ($set3 as $set3Key => &$set3Value) {
            if ($set1Value[0] === $set3Value[0]) {
                //echo "set1-set3: " . $set1Value[0] . "<br />";
                $aggregatedSet[$k][3] = $aggregatedSet[$k][3] + $set3Value[3];
                $set3Value[4] = 'y';
                break 1;
            }
        }

        $k++; //add 1 to aggregatedSet array element
    }

    //***************
    //COMPARE 2ND RESULT SET WITH 3RD RESULT SET
    //  LOOP THROUGH 2ND RESULT SET
    //    CREATE ENTRY IN AGGREGATED RESULT SET FOR EACH NON-SEEN ENTRY
    //    COMPARE EACH NON-SEEN ENTRY TO 3RD RESULT SET
    //      IF URL FOUND/SEEN -
    //        SUM TOTAL IN AGGREGATED SET
    //        MARK AS SEEN IN 3RD RESULT SET
    foreach ($set2 as $set2Key => &$set2Value) {
        //echo $set2Value[4] . "<br />";
        if ($set2Value[4] === 'n') {
            $aggregatedSet[$k] = (
                array($set2Value[0], $set2Value[1], 
                    $set2Value[2], $set2Value[3], 
                    $set2Value[5], $set2Value[6], "")); 
                    //add each set2Result to aggregated Array
            
            foreach ($set3 as $set3Key => &$set3Value) {
                if ($set2Value[0] === $set3Value[0]) {
                    //echo "set2-set3: " . $set2Value[0] . "<br />";
                    $aggregatedSet[$k][3] = $aggregatedSet[$k][3] + $set3Value[3];
                    $set3Value[4] = 'y';
                    break 1;
                }
            }
            $k++; //add 1 to aggregatedSet array element
        }
    }

    //***************
    //ADD REMAINING NON-SEEN ENTRIES FROM 3RD SEARCH ENGINE
    //  LOOP THROUGH 3RD RESULT SET AND
    //  ADD ANY NON-SEEN ENTRIES (VALUE[4] == 'n')
    foreach ($set3 as $set3Key => &$set3Value) {
        //echo $set3Value[4] . "<br />";
        if ($set3Value[4] === 'n') {
            $aggregatedSet[$k] = (
                array($set3Value[0], $set3Value[1], 
                    $set3Value[2], $set3Value[3], 
                    $set3Value[5], $set3Value[6], "")); 
                    //add each set3Result to aggregated Array
            $k++;
        }
    }
    //******************************************************************************
  
    //******************************************************************************
    /**
     * function used by usort to order array
     * 
     * @param array    $a array to be sorted
     * @param function $b callable function for usort
     * 
     * @return void 
     */
    function compareValues($a, $b)
    {
        //SORT RESULTS BY RANK
        //Code derived from: http://ie1.php.net/usort
        
        //Compare based on element 3 which is storing the score
        if ($a[3] === $b[3]) {
            return 0; //same priority
        } elseif ($a[3] > $b[3]) {
            return -1; //a has higher priority than b
        } else {
            return 1; //b has higher priority than a
        }
    }
    usort($aggregatedSet, "compareValues");
    //******************************************************************************
}
?>
