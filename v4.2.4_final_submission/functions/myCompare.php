<?php
/**
 * Function used by usort to order array
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
 * function used by usort to order array
     * 
     * @param array    $a array to be sorted
     * @param function $b callable function for usort
     * 
     * @return void 
     */
function myCompare($a, $b)
{
    //SORT RESULTS BY RANK
    //Code derived from: http://ie1.php.net/usort

    //Compare based on element 1 which is storing the score
    if ($a[1] === $b[1]) {
        return 0; //same priority
    } elseif ($a[1] > $b[1]) {
        return -1; //a has higher priority than b
    } else {
        return 1; //b has higher priority than a
    }
}
?>
