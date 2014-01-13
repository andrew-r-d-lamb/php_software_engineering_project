<?php
/**
 * Function to remove duplicates from an array
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
 * Function takes in 2 arrays by reference, 
 * copies original array into a new array removing duplicates
 * and rescoring the rank and returns the new array by reference
 * 
 * @param array &$originalArray array containing duplicates
 * @param array &$uniqueArray   array to populate with unique URLs
 * 
 * @return void
 */
function removeDuplicates(&$originalArray, &$uniqueArray) 
{
    $keysArray = array(); //used to store a list of seen URLs
    
    $rank = 100; //used to re-rank documents
    
    //loop through the original result set
    foreach ($originalArray as $value) {
        //(URL is at value[0] and will be used as a key)
        //if url is not in keys array
        if (!in_array($value[0], $keysArray)) {
            $keysArray[] = $value[0]; //put url in keys array
            $uniqueArray[] = array(
                $value[0], //trimmed URL
                $value[1], //title
                $value[2], //snippet
                $rank--,   //new rank/score
                $value[4], //found y/n
                $value[5]); //original URL
        }
    }
}
?>
