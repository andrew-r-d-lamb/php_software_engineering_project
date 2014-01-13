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
    //(Non-Aggregated Arrays have the following elements:
    //value[0]Trimmed URL, value[1] Title, value[2] Snippet, value[3] Score, 
    //value[4] Y/N Seen, value[5] Original URL, value[6] Cleaned Snippet placeholder)
    if ($_POST['result_type']==='Non-Aggregated' ) {
        //Loop through Array and check that values have been stored in it:
        foreach ($arrayName as $key => $value) {
            echo "<p style=\"text-align:justify\">
                <a href=\"$value[5]\">$value[1]</a><br />" 
                . $value[2] //snippet
                . "<br />Score: <strong>" 
                . number_format($value[3], 2, '.', '') //score
                . "</strong>" 
                . "<br /><a href=\"#top\"><small>new search</small></a></p>";
        }
    } else if ($_POST['result_type']==='Aggregated'
        || $_POST['result_type']==='Weighted'
    ) {
        //Display Aggregated or Weighted results:
        //(Aggregated Arrays have the following elements:
        //value[0]Trimmed URL, value[1] Title, value[2] Snippet, value[3] Score, 
        //value[4] Y/N Seen, value[5] Original URL, 
        //value[6] Cleaned Snippet placeholder, value[7] coordinate placeholder)
        foreach ($arrayName as $key => $value) {
            echo "<p class=\"text-center\">
                <a href=\"$value[4]\">$value[1]</a><br />"
                . $value[2] //snippet
                . "<br />Score: <strong>" . number_format($value[3], 2, '.', '') 
                . "</strong>"
                . "<br /><a href=\"#top\"><small>new search</small></a></p>";
        }
    } else {
        //Display Clustered Results
        //(Clustered Arrays have the following elements:
        //value[0]Trimmed URL, value[1] Title, value[2] Snippet, value[3] Score, 
        //value[4] Original URL, value[5] Cleaned Snippet, 
        //value[6] Coordinate)
        foreach ($arrayName as $key => $value) {
            echo "<p style=\"text-align:justify\">
                <a href=\"$value[4]\">$value[1]</a><br />"
                . $value[2] //snippet
                . "<br />Score: <strong>" . number_format($value[3], 2, '.', '') 
                . "</strong>"
                . "<br /><a href=\"#top\"><small>new search</small></a></p>";
        }
    }
}
?>
