<?php
/**
 * Function to display some of the contents of an array passed to it
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
 * Function takes in an array by value, and displays contents
 * 
 * @param array $arrayName array to display
 * 
 * @return void
 */
function displayResults($arrayName) 
{
    //Display Non-aggregated results:  
    if ($_POST['result_type']==='Non-Aggregated') {
        //Loop through Array and check that values have been stored in it:
        foreach ($arrayName as $key => $value) {
            echo "<p>Original URL: " . $value[5]
                . "<br />Rank/Score: " . number_format($value[3], 2, '.', '')
                . "</p>";
        }
    } else {
        //Display Aggregated results:
        foreach ($arrayName as $key => $value) {
            echo "<p>Original URL: " . $value[4]
                . "<br />Rank/Score: " . number_format($value[3], 2, '.', '')
                . "</p>";
        }
    }
}
?>
