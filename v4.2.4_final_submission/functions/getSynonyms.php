<?php
/**
 * Function to get synonyms from BIG Huge Thesaurus
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
 * Function takes in an array to populate and looks up the query string for synonyms
 * 
 * @param string $word          the word to lookup
 * @param array  &$resultsArray the array to populate
 * 
 * @return void
 */
function getSynonyms($word, &$resultsArray)
{
    $rootUri = 'http://words.bighugelabs.com/api/2/';
    $acctKey = 'c090d9eaf4465a2cff1a8026034c50d9';
    $query = '/' . $word . '/';
    $format = 'php';

    $requestUri = $rootUri . $acctKey . $query . $format;
    $results = file_get_contents($requestUri);

    $resultsArray = unserialize($results);
}
//http://words.bighugelabs.com/api/2/c090d9eaf4465a2cff1a8026034c50d9/cow/php
?>
