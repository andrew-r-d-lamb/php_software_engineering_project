<?php
/**
 * Function to apply a weight to Google, Bing and Blekko's Results
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
 * Function takes in an array by reference, the weight to be applied and
 * applies the weight
 * 
 * @param array &$set    first set of results
 * @param float $wsumSet weight to be applied
 * 
 * @return void
 */
function applyWeight(&$set, $wsumSet)
{
    foreach ($set as $key => &$value) {
        $value[3] = round(($value[3] * $wsumSet) / 100, 3);
    }
}
?>
