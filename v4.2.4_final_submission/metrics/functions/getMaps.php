<?php
/**
 * Function to calculate the Mean Average Precision Scores and add to array
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
 * Function takes in an array by reference, and populates it with results from Bing
 * 
 * @param array  &$arrayToPopulate array to populate
 * @param string $dataArray        array of data to use for calculations
 * 
 * @return void
 */
function getMaps(&$arrayToPopulate, $dataArray) 
{
    foreach ($dataArray as $key => $value) {
        $mapSum = 0;
        foreach ($dataArray[$key] as $subKey => $subValue) {
            $mapSum = $mapSum + $subValue[9];
        }
        $mapSum = $mapSum / 50;
        $arrayToPopulate[] = array($key, $mapSum);
    }
}
?>