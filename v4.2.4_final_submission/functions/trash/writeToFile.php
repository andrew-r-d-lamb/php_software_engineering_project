<?php
/**
 * Function to serialize arrays and send them to files
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
 * Function takes in an array by reference and a filename,
 * Serializes the array and sends it to the file
 * 
 * @param array  &$resultSet set of results to send to file
 * @param string $directory  name of directory to store file in
 * @param string $fileName   name of file to store array in
 * 
 * @return void
 */
function writeToFile(&$resultSet, $directory, $fileName)
{
    $fp = fopen("$directory/$fileName.txt", 'w'); //open file
    $resultSetStr = serialize($resultSet);
    fwrite($fp, $resultSetStr);
    fclose($fp);
}
?>
