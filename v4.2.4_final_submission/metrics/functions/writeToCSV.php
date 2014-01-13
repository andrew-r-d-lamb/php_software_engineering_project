<?php
/**
 * Function to write metrics results to a csv file
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
 * Function takes in metrics array by reference, a directory name and a filename
 * 
 * @param array  &$metricsSet  set of metrics to send to file
 * @param array  &$metricsMaps MAP scores to send to file
 * @param string $directory    name of directory to store file in
 * @param string $fileName     name of file to store csv results in
 * 
 * @return void
 */
function writeToCSV(&$metricsSet, &$metricsMaps, $directory, $fileName)
{
    $fp = fopen("$directory/$fileName.csv", 'w'); //open file
    
    //initialize temporary variables
    $i=0; //counter for MAP scores 
    $engine = ''; //name of engine
    $mapStr = ''; //to store temp csv string for MAPS
    $metricsStr = ''; //to store temp csv string for metrics
    
    $headerStr = "Engine Name,Query Number,Precision@3,Precision@5,Precision@10,"
        . "Precision@20,Precision@25,Precision@50,Precision@MaxCount,"
        . "Recall@MaxCount,Average Precision,F-Measure,Document Count,Query"
        . "\n";    
    
    foreach ($metricsSet as $key => $value) { 
        $engine = "Engine Name," . $key . "\n";
        $mapStr = "Mean Average Precision," . $metricsMaps[$i++][1] . "\n";
        fwrite($fp, $engine);
        fwrite($fp, $mapStr);
        fwrite($fp, $headerStr);
        $engine = $key;
        foreach ($metricsSet[$key] as $subKey => $subValue) {
            $metricsStr = "$engine,$subValue[0],"
                . number_format($subValue[2], 5, '.', '') . ","
                . number_format($subValue[3], 5, '.', '') . ","
                . number_format($subValue[4], 5, '.', '') . ","
                . number_format($subValue[5], 5, '.', '') . ","
                . number_format($subValue[6], 5, '.', '') . ","
                . number_format($subValue[7], 5, '.', '') . ","
                . number_format($subValue[8], 5, '.', '') . ","
                . number_format($subValue[11], 5, '.', '') . ","
                . number_format($subValue[9], 5, '.', '') . ","
                . number_format($subValue[10], 5, '.', '') . ","
                . $subValue[12] . ","
                . $subValue[1] . "\n";
            fwrite($fp, $metricsStr);
            $metricsStr = ""; //reset value of metrics string
        }
        $engine = ""; //reset value of engine
        $mapStr = ""; //reset value of map string
    }
    fclose($fp);
}
?>