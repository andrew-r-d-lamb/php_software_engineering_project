<?php
/**
 * Simple program to test the porterStemmer function
 * 
 * PHP version 5
 * 
 * @category PHP
 * @package  PHP_Metasearch_Engine
 * @author   Andrew Lamb <andrew.r.d.lamb@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://csserver.ucd.ie/~01421662/
 */
require 'porterStemmer.php';

$filecontents = file_get_contents('../tempFiles/test_vocab.txt');
//var_dump($filecontents);
$filewords = explode("\n", $filecontents);
//var_dump($filewords);


$stem = '';
foreach ($filewords as $value) {
    echo "orig: " . $value . "<br />stemmed: ";
    $stem = PorterStemmer::Stem($value);
    echo $stem . "<br /><br />";
}
?>
