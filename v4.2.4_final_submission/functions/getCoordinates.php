<?php
/**
 * Function to calculate coordinates for each snippet 
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
 * Function to calculate coordinates for each snippet 
 * 
 * @param array &$aggregatedArray array with stemmed snippets needs a coordinate
 * @param array &$wordCollection  array for all unique snippet words and their count
 * 
 * @return null
 */
function getCoordinates(&$aggregatedArray, &$wordCollection)
{
    $tempCollection = array();
    
    //create an array of all the words in all the snippets
    foreach ($aggregatedArray as $key => $value) {
        $tempSnippet = explode(" ", $value[5]); //put snippet into array of words
        foreach ($tempSnippet as $value) {
            $tempCollection[] = $value;
        }
    }
    
    //create an associative array with key as word 
    //and value as the count of that particular word
    $tempUniqueCollection = array_count_values($tempCollection);
        
    ksort($tempUniqueCollection); //sort the array alphabetically by key
    unset($tempUniqueCollection['']); //remove null entry from array
    
    //get a count for the most frequent term
    //this will be used for normalizing the term frequency (tf = tf/maxFrequency)
    $maxFrequency = max($tempUniqueCollection);
    
    //get a count of document frequency for each term 
    //(no of documents that each term appears in)
    $tempCount = 0;
    $tempWordCollection = array();
    foreach ($tempUniqueCollection as $collKey => $collVal) {
        foreach ($aggregatedArray as $aggKey => $aggVal) {
            if (preg_match("/\b$collKey\b/", $aggVal[5])) {
                $tempCount = $tempCount + 1;
            }
        }
        $tempWordCollection[] = array($collKey, $collVal, $tempCount);
        $tempCount = 0;
    }
    //each element of temp word collection now contains 
    //  [0] (word/term)
    //  [1] (total count of each term)
    //  [2] (document frequency of each term)
    
    //get total number of documents in collection
    $totalDocs = count($aggregatedArray);
    
    //calculate idf for each term using log base 2
    $getLogOf = 0.0; //initiate float getLogOf
    foreach ($tempWordCollection as $key => $value) {
        $getLogOf = $totalDocs/$value[2];
        $tempIdf = log($getLogOf, 2);
        //put term, total count, document frequency of each term and 
        //inverse document frequency of each term into the wordCollection array
        $wordCollection[] = array($value[0], $value[1], $value[2], $tempIdf);
    }
    //each element of word collection now contains 
    //  [0] (word/term)
    //  [1] (total count of each term)
    //  [2] (document frequency of each term)
    //  [3] (inverse document frequency of each term)
    
    //Get the coordinates of each snippet
    $tempCoordinate = ""; //initialize a temporary variable to store coordinate
    $tfCount = 0; //counter variable for term frequency
    $termWeight = 0.0; //initialize term weight variable
    $tempExplodeSnippet = array();
    //loop through word collection
    foreach ($wordCollection as $collKey => $collVal) {
        //loop through aggregated array
        foreach ($aggregatedArray as $aggKey => &$aggVal) {
            //explode each snippet to check for words
            $tempExplodeSnippet = explode(" ", $aggVal[5]);
            //loop through each exploded snippet
            foreach ($tempExplodeSnippet as $snipValue) {
                if ($snipValue === $collVal[0]) {
                    $tfCount++; //count occurrence of each word
                }
            }
            //calculate term weight to be applied 
            //normalize the term frequency($tfCount) score
            //(term frequency / frequency of most frequent term)
            $normalizedtfCount = $tfCount / $maxFrequency;
            //(normalized term frequency:
            //$normalizedtfCount) / inverse document frequency(collVal[3])
            $termWeight = $normalizedtfCount / $collVal[3]; 
            
            $aggVal[6] = $aggVal[6] . $termWeight . ","; //concatenate each number
            $tfCount = 0;
            $termWeight = 0.0;
        }
    }    
}
?>
