<?php
/**
 * Function to read serialized array from a file 
 * given an array to populate and the filename
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
 * Function takes in an array by reference, the filename, and populates the array
 * from a file
 * 
 * @param array  &$resultSet array to populate
 * @param string $fileName   name of file to open
 * 
 * @return void
 */
function readFromFile(&$resultSet, $fileName) 
{
    $fp = fopen("../tempFiles/$fileName", 'r'); //open file
    $fileContents = fread($fp, \filesize("../tempFiles/$fileName"));
    $resultSet = unserialize($fileContents);
    fclose($fp);
}
?>
