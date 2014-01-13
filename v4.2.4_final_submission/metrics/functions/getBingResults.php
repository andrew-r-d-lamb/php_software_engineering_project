<?php
/**
 * Function to get results from Bing and store in an array
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
function getBingResults(&$results, $query) 
{
    //API Key
    $acctKey = 'SW+6Or+DdEasDjYO8QqOglHbjH0t8/dr+SOUfp6vxxs';

    $rootUri = 'https://api.datamarket.azure.com/Bing/Search'; // Bing Root Address
    
    $numResultsBing = 50;  
    
    //From Bing's API documentation: 
    //Encode the query and the single quotes that must surround it.
    $query = urlencode("'$query'"); 

    // Assign 'Web' as the service operation variable
    $serviceOp = 'Web';

    // Construct the URI for the query.
    // Uri for 1st x number of results:
    $requestUri 
        = "$rootUri/$serviceOp?\$format=json&Query=$query&\$top=$numResultsBing";

    // Encode the credentials and create the stream context. (Required by Bing).
    //************CODE SUPPLIED BY BING API DOCUMENTATION***********************
    $auth = base64_encode("$acctKey:$acctKey");
    $data = array(
        'http' => array(
            'request_fulluri' => true,
            // ignore_errors can help debug – remove for production. 
            // This option added in PHP 5.2.10
            'ignore_errors' => true,
            'header' => "Authorization: Basic $auth")
    );
    $context = stream_context_create($data);

    //Get the response from Bing.
    $response = file_get_contents($requestUri, 0, $context);   
    //**************************************************************************

    //Process the json response. 
    $jsonObj = json_decode($response); //Decode json

    //Error check for problems with jsonObj - if failure exit with error code
    if (is_null($jsonObj)) {
        die("Bing: Json decoding failed with error: " . json_last_error());
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
    foreach ($jsonObj->d->results as $item) {
        //if (isset(something)) 
        //used for error handling case of "Undefined property:
        //stdClass::$snippet for results with no snippet included
        if (isset($item->Url) && isset($item->Title)) {
            //Copy individual items into temporary variables
            $originalUrl = $item->Url; //copy of original url
            //copy the revised url:
            $url = preg_replace(
                $urlItemsSearchFor, $urlItemsReplaceWith, $item->Url
            ); 
            $urlTitle = $item->Title; //copy the url title
        }
        if (isset($item->Description)) {
            //Copy individual items into temporary variables
            $snippet = $item->Description; //copy the snippet
        } else {
            //Copy empty value to snippet
            $snippet = ''; //because no snippet available
        }

        //add variables to the array.  key by URL
        $results[$i++] = (array(
            $url, $urlTitle, $snippet, $j--, $urlFound, $originalUrl));
    }

    //***************************************************************************
    //Second run for next 50 results
    
    $requestUri 
        = "$rootUri/$serviceOp?\$format=json&Query=$query&\$skip=50";

    // Get the response from Bing.
    $response = file_get_contents($requestUri, 0, $context); 

    //Process the json response. 
    $jsonObj = json_decode($response); //Decode json

    //Error check for problems with jsonObj - if failure exit with error code
    if (is_null($jsonObj)) {
        die("Json decoding failed with error: " . json_last_error());
    }

    //Loop through decoded jsonObj and store desired results in an array
    foreach ($jsonObj->d->results as $item) {
        //if (isset(something)) 
        //used for error handling case of "Undefined property:
        //stdClass::$snippet for results with no snippet included
        if (isset($item->Url) && isset($item->Title)) {
            //Copy individual items into temporary variables
            $originalUrl = $item->Url; //copy of original url
            //copy the revised url:
            $url = preg_replace(
                $urlItemsSearchFor, $urlItemsReplaceWith, $item->Url
            ); 
            $urlTitle = $item->Title; //copy the url title
        }
        if (isset($item->Description)) {
            //Copy individual items into temporary variables
            $snippet = $item->Description; //copy the snippet
        } else {
            //Copy empty value to snippet
            $snippet = ''; //because no snippet available
        }

        //add variables to the array.  key by URL
        $results[$i++] = (array(
            $url, $urlTitle, $snippet, $j--, $urlFound, $originalUrl));
    }
}
//***************************************************************************
?>