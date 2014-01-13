<?php
/**
 * Hidden Form for displaying alternative search options
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
 * Function generates a form and populates it with radio buttons for each of the
 * synonyms contained in a synonym array file
 * 
 * @param type $synonymArray array containing synonyms
 * 
 * @return void
 */
function listSynonyms($synonymArray)
{
    echo "<div class=\"row-fluid\" style=\"text-align:center\">";
        //<div class=\"span12\">"
    echo "<div class=\"span4\">";
    echo "</div>";
    echo "<div class=\"span4\">";
    echo "<form class=\"form-search\" method=\"POST\" action=\"index.php\"> 
        <label for=\"alternatives\">Alternatives:</label><br />";
    
    foreach ($synonymArray as $key => $value) {
        echo "<label for=\"$key" 
            . "s\"><strong>" 
            . $key 
            . "s</strong>:</label><br />";
        foreach ($value["syn"] as $innerValue) {
            echo "<label class=\"radio inline\">"
            . '<input name="alternative" type="radio" value="' 
            . $innerValue 
                . '" /> ' 
                . $innerValue . '</label>';
        }
        echo "<br /><br />";
    }
    echo '<input class="btn btn-inverse" name="synonymSearch" type="submit" 
             value="Re-Write" /></form>';
    echo "</div>";
    echo "<div class=\"span4\">";
    echo "</div>";
    echo "</div>";
}
?>
