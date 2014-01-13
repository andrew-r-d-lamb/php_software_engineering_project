<?php
/**
 * Function to read from a serialized file and populate an array
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
 * Function to read from a serialized file and populate an array
 * 
 * @param array  &$resultSet    array to populate
 * @param string $directoryName directory location where file is stored
 * @param string $fileName      name of the file to read from
 * 
 * @return void
 */
function readFromFile(&$resultSet, $directoryName, $fileName) 
{
    $fp = fopen("$directoryName/$fileName", 'r'); //open file
    $fileContents = fread($fp, filesize("$directoryName/$fileName"));
    $resultSet = unserialize($fileContents);
    fclose($fp);
}
?>
