<?php
/**
 * Function to get results from Blekko and store in an array
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
 * Function takes in an array by reference, and populates it with results from Bing
 * 
 * @param array  &$results array to populate
 * @param string $query    the query to search for
 * 
 * @return void
 */
function getBlekkoResults(&$results, $query) 
{
    //API Key
    $acctKey = 'f4c8acf3';

    $rootUri = 'http://blekko.com/ws/?'; //Blekko API root address

    $numResults = 100;  //Set number of results desired (1-100).

    //From Bing's API documentation: 
    //Encode the query and the single quotes that must surround it.
    $query = urlencode("'$query'");
    
    $query = str_replace("NOT+", "-", $query); //NOT is '-' FOR BLEKKO
    $query = str_replace("+OR+", "+or+", $query); //OR for Blekko can mess up results
       
    // Construct the full URI for the query.
    //NOT DOESN'T WORK WHEN USING THE API KEY, 
    //but David Lillis 10/07/2013 by email says to use this way:
    $requestUri 
        = "$rootUri" . "q=$query+/json+/ps=$numResults&auth=$acctKey";

    //NOT WILL WORK IF YOU DON'T USE THE API KEY:
    //$requestUri 
        //= "$rootUri" . "q=$query+/json+/ps=$numResults";

    //Get Results Using cURL
    $ch = curl_init(); //Initiate cURL
    curl_setopt($ch, CURLOPT_URL, $requestUri); //set the URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return transfer as a string
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
        die("Blekko: Curl failed with error: " . curl_error($ch));
    }

    //Process the json response. 
    $jsonObj = json_decode($data); //decode json

    //Error check for problems with jsonObj - if failure exit with error code
    if (is_null($jsonObj)) {
        die("Blekko: Json decoding failed with error: " . json_last_error());
    }

    //Variables to temporarily store individual result elements
    $url = '';
    $urlTitle = '';
    $snippet = '';
    $originalUrl = '';
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

    //Loop through decoded jsonObj and store desired results in an array
    foreach ($jsonObj->RESULT as $item) {
        //if (isset(something)) 
        //used for error handling case of "Undefined property:
        //stdClass::$snippet for results with no snippet included
        if (isset($item->url) && isset($item->url_title)) {
            //Copy individual items into temporary variables
            $originalUrl = $item->url; //copy of original url
            //copy the revised url
            $url = preg_replace(
                $urlItemsSearchFor, $urlItemsReplaceWith, $item->url
            ); 
            $urlTitle = $item->url_title; //copy the url title
        }
        if (isset($item->snippet)) {
            //Copy individual items into temporary variables
            $snippet = $item->snippet; //copy the snippet
        } else {
            //Copy empty value to snippet
            $snippet = ''; //because no snippet available
        }

        //add variables to the array.  
        $results[$i++] = (array(
            $url, $urlTitle, $snippet, $j--, $urlFound, $originalUrl));
    }
}
?>
