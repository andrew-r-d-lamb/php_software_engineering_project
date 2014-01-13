<?php
/**
 * Main Program File
 * 
 * PHP version 5
 * 
 * @category PHP
 * @package  PHP_Metasearch_Engine
 * @author   Andrew Lamb <andrew.r.d.lamb@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://csserver.ucd.ie/~01421662/
 */
//Include header file
require 'includes/searchHeader.php';

//increase max_execution_time for processing clustering
ini_set('max_execution_time', 600);

//check memory usage
//(not available on csserver)
//echo "<br /> " . memory_get_peak_usage() . "<br /> ";

//IF A QUERY HAS BEEN POSTED AND IS NOT EMPTY, PROCESS QUERY
if (isset($_POST['query']) && !empty($_POST['query'])) {
    //INCLUDE FUNCTIONS:
    include 'functions/getBlekkoResults.php'; //get results from Blekko
    include 'functions/getGoogleResults.php'; //get results from Google
    include 'functions/getBingResults.php'; //get results from Bing
    include 'functions/removeDuplicates.php'; //remove duplicate URLs
    include 'functions/displayResults.php'; //display results
    include 'functions/aggregateResults.php'; //aggregate the results (Borda-Fuse)
    include 'functions/calcWeight.php'; //calculate the weight for each search engine
    include 'functions/applyWeight.php'; //apply the weight to the results
    include 'functions/cleanQuery.php'; //clean the query entered
    include 'functions/getFirstWord.php'; //get first query word
    include 'functions/getSynonyms.php'; //get synonyms for the word
    include 'functions/prepareSnippet.php'; //clean snippet and remove stop words
    include 'functions/porterStemmer.php'; //porterStemmingClass (not mine)
    include 'functions/getCoordinates.php'; //calculate coordinates
    include 'functions/fillClusters.php'; //put results into clusters
    
    //Arrays to store results
    $blekkoResultsOrig = array(); //original results (may contain duplicates)
    $googleResultsOrig = array(); //original results (may contain duplicates)
    $bingResultsOrig = array ();  //original results (may contain duplicates)
    $blekkoResults = array(); //results with duplicates removed
    $googleResults = array(); //results with duplicates removed
    $bingResults = array();   //results with duplicates removed
    $aggregatedResults = array(); //array to store aggregated results in
    $synonyms = array(); //array to store synonyms retrieved

    //CLEAN THE QUERY STRING FOR SUGGEST WORDS
    $queryEntered = $_POST['query'];
    $cleanedQuery = cleanQuery($queryEntered);

    
    //******************************************************************************
    //Turn off Warning-reporting for the following warning on CSSERVER:
    //Warning: file_get_contents() [function.file-get-contents]: 
    //              SSL: fatal protocol error in ...
    //error_reporting(E_ERROR | E_PARSE);
    error_reporting(0);
    //******************************************************************************

    //******************************************************************************
    //IF WORD SUGGESTIONS HAVE BEEN REQUESTED DISPLAY ALTERNATIVES
    if (isset($_POST['reWrite']) && $_POST['reWrite']!='') {
        //ONLY WANT THE FIRST WORD FOR OUR LOOKUP
        $firstQueryWord = getFirstWord($cleanedQuery);
        
        //LOOKUP THE WORD
        getSynonyms($firstQueryWord, $synonyms);
        
        //INCLUDE PHP FUNCTION WHICH CREATES A FORM BASED ON THE SYNONYM ARRAY
        include 'functions/synonymForm.php';
        
        //RUN FUNCTION TO CREATE FORM OF SYNONYMS
        listSynonyms($synonyms);        
    } else {
        //IF REWRITE IS NOT REQUESTED, PROCESS QUERY:
        //CALL FUNCTIONS TO GET RESULTS FROM SEARCH ENGINES
        getBlekkoResults($blekkoResultsOrig); //get Blekko
        getGoogleResults($googleResultsOrig); //get Google
        getBingResults($bingResultsOrig); //get Bing
        //**************************************************************************
        
        //**************************************************************************
        //CALL FUNCTIONS TO REMOVE DUPLICATE URLS AND RE-SCORE DOCUMENTS
        removeDuplicates($blekkoResultsOrig, $blekkoResults);
        removeDuplicates($googleResultsOrig, $googleResults);
        removeDuplicates($bingResultsOrig, $bingResults);
        //**************************************************************************

        //**************************************************************************
        //(IF NON-AGGREGATED CHOSEN THEN DISPLAY RESULTS:)
        if ($_POST['result_type']==='Non-Aggregated') { 
            echo "<div class=\"row-fluid\">"
            . "<div class=\"span4\">";
            echo "<h2 class=\"text-center\">Bing</h2>";
            displayResults($bingResults); //display Bing
            echo "</div>";
            
            echo "<div class=\"span4\">";            
            echo "<h2 class=\"text-center\">Google</h2>";
            displayResults($googleResults); //display Google
            echo "</div>";
            
            echo "<div class=\"span4\">";
            echo "<h2 class=\"text-center\">Blekko</h2>";
            displayResults($blekkoResults); //display Blekko
            echo "</div>";
            echo "</div>";
        }
        //**************************************************************************

        //**************************************************************************
        //(IF AGGREGATE CHOSEN THEN:)
        if ($_POST['result_type']==='Aggregated') {
            //AGGREGATE the Results using Borda-Fuse
            //NOTE THAT THE ORDER THE ARRAYS ARE SENT IN TO BE AGGREGATED MATTERS 
            //FOR ANY RESULTS THAT HAVE THE SAME COMBINED SCORE/RANK
            aggregateResults(
                $googleResults, $bingResults, 
                $blekkoResults, $aggregatedResults
            );

            //DISPLAY aggregated results:
            echo "<div class=\"row-fluid\">"
            . "<div class=\"span3\">";
            echo "</div>";
            echo "<div class=\"span6\">";
            echo "<h2 class=\"text-center\">Aggregated</h2>";
            displayResults($aggregatedResults); //display combined
            echo "</div>";
            echo "<div class=\"span3\">";
            echo "</div>";
            
        }
        //**************************************************************************

        //**************************************************************************
        //(IF WEIGHTED AGGREGATION IS CHOSEN:)
        if ($_POST['result_type']==='Weighted') {
            //initialize variables to rank search engines
            $wsumGoogle = $wsumBing = $wsumBlekko = 0.00; 

            //CALCULATE THE WEIGHT FOR EACH SEARCH ENGINE
            calcWeight(
                $googleResults, $bingResults, 
                $blekkoResults, $wsumGoogle, 
                $wsumBing, $wsumBlekko
            );

            //APPLY THE WEIGHT TO EACH OF THE SEARCH ENGINES RESULTS
            applyWeight($googleResults, $wsumGoogle);
            applyWeight($bingResults, $wsumBing);
            applyWeight($blekkoResults, $wsumBlekko);

            //AGGREGATE the Results using Borda-Fuse
            aggregateResults(
                $googleResults, $bingResults, 
                $blekkoResults, $aggregatedResults
            );

            //DISPLAY weighted aggregated results:
            echo "<div class=\"row-fluid\">"
            . "<div class=\"span3\">";
            echo "</div>";
            echo "<div class=\"span6\">";          
            echo "<h2 class=\"text-center\">Weight Applied:</h2>";
            echo "<h3 class=\"text-center\">";
            echo "Google: " . $wsumGoogle 
                . "% Bing: " . $wsumBing 
                . "% Blekko: " . $wsumBlekko . "%</h3>";
            displayResults($aggregatedResults); //display combined
            echo "</div>";
            echo "<div class=\"span3\">";
            echo "</div>";            
        } 
        //**************************************************************************
        
        //**************************************************************************
        //(IF CLUSTERING IS CHOSEN:)
        if ($_POST['result_type']==='Clustered') {
            //AGGREGATE the Results using Borda-Fuse
            //NOTE THAT THE ORDER THE ARRAYS ARE SENT IN TO BE AGGREGATED MATTERS 
            //FOR ANY RESULTS THAT HAVE THE SAME COMBINED SCORE/RANK
            aggregateResults(
                $googleResults, $bingResults, 
                $blekkoResults, $aggregatedResults
            );
            
            //Remove Stop Words
            foreach ($aggregatedResults as $key => &$value) {
                $value[5] = prepareSnippet($value[2]);
            }
            
            //Stem the results
            //$stem = PorterStemmer::Stem($value);
            foreach ($aggregatedResults as $key => &$value) {
                $tempArray = explode(" ", $value[5]);
                foreach ($tempArray as &$tempValue) {
                    $tempValue = PorterStemmer::Stem($tempValue);
                }
                $value[5] = implode(" ", $tempArray);
            }
            
            //Get Coordinates
            $wordCollection = array();
            getCoordinates($aggregatedResults, $wordCollection);
            
            //Cluster the results
            $cluster1 = array();
            $cluster2 = array();
            $cluster3 = array();
            $cluster4 = array();
            fillClusters(
                $aggregatedResults, $cluster1, 
                $cluster2, $cluster3, $cluster4
            );
            
            //*********************************************************************
            //EXPERIMENTAL ATTEMPT AT NAMING CLUSTERS
            //Remove Stop Words from clusters
            foreach ($cluster1 as $key => &$value) {
                $value[5] = prepareSnippet($value[2]);
            }
            foreach ($cluster2 as $key => &$value) {
                $value[5] = prepareSnippet($value[2]);
            }
            foreach ($cluster3 as $key => &$value) {
                $value[5] = prepareSnippet($value[2]);
            }
            foreach ($cluster4 as $key => &$value) {
                $value[5] = prepareSnippet($value[2]);
            }
            
            /*
            //Stem the results
            //$stem = PorterStemmer::Stem($value);
            foreach ($cluster1 as $key => &$value) {
                $tempArray = explode(" ", $value[5]);
                foreach ($tempArray as &$tempValue) {
                    //echo "$tempValue <br />";
                    $tempValue = PorterStemmer::Stem($tempValue);
                    //echo "$tempValue <br />";
                }
                $value[5] = implode(" ", $tempArray);
            }
            foreach ($cluster2 as $key => &$value) {
                $tempArray = explode(" ", $value[5]);
                foreach ($tempArray as &$tempValue) {
                    //echo "$tempValue <br />";
                    $tempValue = PorterStemmer::Stem($tempValue);
                    //echo "$tempValue <br />";
                }
                $value[5] = implode(" ", $tempArray);
            }
            foreach ($cluster3 as $key => &$value) {
                $tempArray = explode(" ", $value[5]);
                foreach ($tempArray as &$tempValue) {
                    //echo "$tempValue <br />";
                    $tempValue = PorterStemmer::Stem($tempValue);
                    //echo "$tempValue <br />";
                }
                $value[5] = implode(" ", $tempArray);
            }
            foreach ($cluster4 as $key => &$value) {
                $tempArray = explode(" ", $value[5]);
                foreach ($tempArray as &$tempValue) {
                    //echo "$tempValue <br />";
                    $tempValue = PorterStemmer::Stem($tempValue);
                    //echo "$tempValue <br />";
                }
                $value[5] = implode(" ", $tempArray);
            }
            */

            include 'functions/getClusterTitlesExperimental.php';
            include 'functions/myCompare.php';
            //Get Main Coordinate TF/IDF
            $titlesCluster1 = "";
            $titlesCluster2 = "";
            $titlesCluster3 = "";
            $titlesCluster4 = "";
            $titlesCluster1 = getClusterTitlesExperimental($cluster1);
            $titlesCluster2 = getClusterTitlesExperimental($cluster2);
            $titlesCluster3 = getClusterTitlesExperimental($cluster3);
            $titlesCluster4 = getClusterTitlesExperimental($cluster4);
           
            //*********************************************************************

            //Display the title:
            echo "<div class=\"row-fluid\">"
            . "<div class=\"span3\">";
            echo "</div>";
            echo "<div class=\"span6\">";
            echo "<h2 class=\"text-center\">Clusters</h2>";
            echo "</div>";
            echo "<div class=\"span3\">";
            echo "</div>";
            echo "</div>";
            
            //Display the results
            echo "<div class=\"row-fluid\">";
            echo "<div class=\"span3\">";
            //echo "<h2 class=\"text-center\">Cluster 1<br /></h2>";
            echo "<p class=\"text-center\"><b>Keywords:"
            . "</b><br />$titlesCluster1<br />";
            displayResults($cluster1);
            echo "</div>";           
            
            echo "<div class=\"span3\">";
            //echo "<h2 class=\"text-center\">Cluster 2<br /></h2>";
            echo "<p class=\"text-center\"><b>Keywords: " 
            . "</b><br />$titlesCluster2<br />";
            displayResults($cluster2);
            echo "</div>";
            
            echo "<div class=\"span3\">";
            //echo "<h2 class=\"text-center\">Cluster 3<br /></h2>";
            echo "<p class=\"text-center\"><b>Keywords: "
            . "</b><br />$titlesCluster3<br />";
            displayResults($cluster3);
            echo "</div>";
            
            echo "<div class=\"span3\">";
            //echo "<h2 class=\"text-center\">Cluster 4<br /></h2>";
            echo "<p class=\"text-center\"><b>Keywords: "
            . "</b><br />$titlesCluster4<br />";
            displayResults($cluster4);
            echo "</div>";
            echo "</div>";
        }
        //**************************************************************************
    }
    //check memory usage
    //(not available on csserver)
    //echo "<br /> " . memory_get_peak_usage() . "<br /> ";
}

//Include footer file
require 'includes/searchFooter.php';
?>