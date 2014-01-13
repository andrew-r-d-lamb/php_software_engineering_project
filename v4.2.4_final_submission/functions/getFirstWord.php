<?php
/**
 * Function to get only the first word entered by the user
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
 * Function called to clean the query passed to it and return a cleaned string
 * 
 * @param string $queryToClean the string to clean
 * 
 * @return string cleaned string first word only
 */
function getFirstWord($queryToClean) 
{
    //REMOVE ALL SPECIAL CHARACTERS
    $keep = '/[^A-Za-z0-9 ]/'; //will replace everything but these
    $replacement = ''; //replace everything else with this
    $cleanedQuery = ''; //variable to return once cleaned
    $cleanedQuery = preg_replace($keep, $replacement, $queryToClean);
    
    //GET FIRST WORD OF A SENTENCE
    //EXPLODE THE STRING INTO AN ARRAY STRIPPING OFF WHITESPACE EITHER SIDE
    $explodedQuery = explode(' ', trim($cleanedQuery));

    return strtolower($explodedQuery[0]); //return the lower case word
}
?>
