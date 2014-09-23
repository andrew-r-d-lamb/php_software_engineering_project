<?php
/**
 * Function to get results from Google and store in an array
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
 * Function takes in an array by reference, and populates it with results from Google
 * 
 * @param array &$results array to populate
 * 
 * @return void
 */
function getGoogleResults(&$results) 
{
    //API Key
    $acctKey = 'account_key_goes_here';

    //Google Search Engine ID
    $engineID = 'search_engine_ID_goes_here'; 
    
    //Google API basic/root address
    $rootUri = 'https://www.googleapis.com/customsearch/v1?'; 
    
    $numResultsGoogle = 10;  //Set number of results desired (1-10).
    $startResultsPosition = 1;  //set initial starting position
    
    //SET VALUE FOR LOOP CONDITION BASED ON NUMBER OF RESULTS REQUESTED:
    if ($_POST['numResults'] === '10') {
        $loopCondition = 1;
    } elseif ($_POST['numResults'] === '50') {
        $loopCondition = 41;
    } elseif ($_POST['numResults'] === '100') {
        $loopCondition = 91;
    }

    //From Bing's API documentation: 
    //Encode the query and the single quotes that must surround it.
    $query = urlencode("'{$_POST['query']}'"); 

    //Replace NOT as required by Google
    $query = str_replace("NOT+", "%2D", $query);

    //Variables to temporarily store individual result elements
    $url = '';
    $urlTitle = '';
    $snippet = '';
    $originalUrl = '';
    $cleanedSnippet = '';
    $i = 0; //incremental key for array
    $j = 100.00; //incremental key for rank    

    //variable to initiate final array elements to 'n'
    //will be used as a check to see if item has been found when comparing URLs
    //if found will be changed to 'y'
    $urlFound = 'n';

    //REGULAR EXPRESSIONS TO BE REPLACED IN URL:
    $urlItemsSearchFor = array(
        '/(http|https|ftp|sftp)\:\/\//', //http|https|ftp|sftp://
        '/(www|www2|www3)\./', //www|www2.
        '{/$}'); //trailing forward slash
    $urlItemsReplaceWith = ''; //Replace with ''    

    //***************************************************************************
    //For loop to get x sets of results from google
    for (
        $startResultsPosition = 1; 
        $startResultsPosition <= $loopCondition; 
        $startResultsPosition = $startResultsPosition + 10) {
        //***********************************************************************
        // Construct the full URI for the query.
        $requestUri 
            = "$rootUri" 
            . "key=$acctKey&cx=$engineID" 
            . "&q=$query&alt=json" 
            . "&num=$numResultsGoogle&start=$startResultsPosition";

        //Get Results Using cURL
        $ch = curl_init(); //Initiate cURL
        curl_setopt($ch, CURLOPT_URL, $requestUri); //set the URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return transfer string
        //*******************************************************
        //ADD CURL CA CERTS FOR WAMP (WORKS ON CSSERVER WITHOUT THESE)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt(
            $ch, CURLOPT_CAINFO, dirname(__FILE__) . '/curl_certs/cacert.pem'
        );
        //*******************************************************

        $data = curl_exec($ch); //get the web page source into $data

        //Error check for problems with cURL - if failure exit with error code
        if ($data === false) {
            die("Google: Curl failed with error: " . curl_error($ch));
        }

        //Process the json response.
        $jsonObj = json_decode($data); //decode json
        
        //Error check for problems with jsonObj - if failure exit with error code
        if (is_null($jsonObj)) {
            die("Google: Json decoding failed with error: " . json_last_error());
        }
        
        //Loop through decoded jsonObj and store desired results in an array
        if (isset($jsonObj->items)) {
            foreach ($jsonObj->items as $item) {
                //if (isset(something)) 
                //used for error handling case of "Undefined property:
                //stdClass::$snippet for results with no snippet included
                if (isset($item->link) && isset($item->title)) {
                    //Copy individual items into temporary variables
                    $originalUrl = $item->link; //copy of original url
                    //copy the revised url
                    $url = preg_replace(
                        $urlItemsSearchFor, $urlItemsReplaceWith, $item->link
                    );
                    $urlTitle = $item->title; //copy the url title
                }
                if (isset($item->snippet)) {
                    //Copy individual items into temporary variables
                    $snippet = $item->snippet; //copy the snippet
                } else {
                    //Copy title to snippet
                    $snippet = $item->title; //because no snippet available
                }

                //add variables to the array.  key by URL
                $results[$i++] = (array(
                    $url, $urlTitle, $snippet, $j--, 
                    $urlFound, $originalUrl, $cleanedSnippet));
            }
        } else {
            $results[] = array("", "", "", 0.000001, "", "NULL", "");            
        }
    } //end of for loop
}
?>