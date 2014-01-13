<?php
/**
 * Function to calculate the primary coordinate and get titles / keywords
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
 * Function to calculate the primary coordinate and get titles / keywords
 * 
 * @param array &$clusteredArray array with stemmed snippets used to get titles
 * 
 * @return string
 */
function getClusterTitlesExperimental(&$clusteredArray)
{
    $tempCollection = array();
    
    //create an array of all the words in all the snippets
    foreach ($clusteredArray as $key => $value) {
        $tempSnippet = explode(" ", $value[5]); //put snippet into array of words
        foreach ($tempSnippet as $value) {
            $tempCollection[] = $value;
        }
    }
   
    //create an associative array with key as word 
    //and value as the count of that particular word
    $tempUniqueCollection = array_count_values($tempCollection);
    //var_dump($tempUniqueCollection);
    
    //ksort($tempUniqueCollection); //sort the array alphabetically by key
    unset($tempUniqueCollection['']); //remove null entry from array
    
    //get a count for the most frequent term
    //this will be used for normalizing the term frequency (tf = tf/maxFrequency)
    $maxFrequency = max($tempUniqueCollection);
    
    //get a count of document frequency for each term 
    //(no of documents that each term appears in)
    $tempCount = 0;
    $tempWordCollection = array();
    foreach ($tempUniqueCollection as $collKey => $collVal) {
        //echo "$collKey<br />";
        foreach ($clusteredArray as $aggKey => $aggVal) {
            if (preg_match("/\b$collKey\b/", $aggVal[5])) {
                $tempCount = $tempCount + 1;
            }
        }
        //echo "word: $collKey, docFrequency: $tempCount<br />";
        $tempWordCollection[] = array($collKey, $collVal, $tempCount);
        $tempCount = 0;
    }
    //each element of temp word collection now contains 
    //  [0] (word/term)
    //  [1] (total count of each term)
    //  [2] (document frequency of each term)
    
    //get total number of documents in collection
    $totalDocs = count($clusteredArray);
    
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
    
    //Calculate the primary coordinate
    $tempCoordinate = ""; //initialize a temporary variable to store coordinate
    $tfCount = 0; //counter variable for term frequency
    $termWeight = 0.0; //initialize term weight variable
    $tempExplodeSnippet = array();
    //loop through word collection
    foreach ($wordCollection as $collKey => &$collVal) {
        $termFrequency = $collVal[1];
        $normalizedTermFrequency = $termFrequency / $maxFrequency;
        //(normalized term frequency:
        //$normalizedtfCount) / inverse document frequency(collVal[3])
        if ($collVal[3] == 0) {
            $collVal[3] = 0.0000000000001;
        }
        $termWeight = $normalizedTermFrequency / $collVal[3]; 
        
        //assign TF/IDF score to each word in the array
        $collVal[1] = $termWeight;   
    }
    
    //sort the array by word score
    usort($wordCollection, "myCompare");
    
    //assign 5 most important keywords to $titles string
    $titles = $wordCollection[0][0] 
        . " " 
        . $wordCollection[1][0]
        . " " 
        . $wordCollection[2][0]
        . " " 
        . $wordCollection[3][0]
        . " " 
        . $wordCollection[4][0]
        . " " 
        ;
    
    //return the titles/keywords
    return $titles;
}
?>
