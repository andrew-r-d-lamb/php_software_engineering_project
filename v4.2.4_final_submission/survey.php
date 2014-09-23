<?php
/**
 * User Survey Page
 * 
 * PHP version 5
 * 
 * @category PHP
 * @package  PHP_Metasearch_Engine
 * @author   Andrew Lamb <andrew.r.d.lamb@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://csserver.ucd.ie/~01421662/
 */

//Turn off error reporting
error_reporting(0);

/**
 * Function takes in email field, checks for irregular characters and returns a
 * boolean value True or False depending on if it is valid or not
 * If valid - sends email (and writes to file)
 * 
 * @param string $field email string to check
 * 
 * @return boolean
 */
function spamcheck($field)
{
    //FUNCTION TAKEN FROM http://www.w3schools.com/php/php_secure_mail.asp
    /*filter_var unsupported on the server!!!
    //filter_var() sanitizes the e-mail
    //address using FILTER_SANITIZE_EMAIL
    $field=filter_var($field, FILTER_SANITIZE_EMAIL);

    //filter_var() validates the e-mail
    //address using FILTER_VALIDATE_EMAIL
    if(filter_var($field, FILTER_VALIDATE_EMAIL)) {
        return TRUE;
    } else {
        return FALSE;
    }
    */
    
    //Clean using regular expression
    $regex 
        = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
    if (preg_match($regex, $field)) {
        return true;
    } else {
        return false;
    }
}
if (isset($_REQUEST['email'])) {
    //check if the email address is invalid
    $mailcheck = spamcheck($_REQUEST['email']);
    if ($mailcheck==false) {
        //echo "Invalid email entered. Please try again";
        include "includes/surveyResultsInvalidEmail.php";
    } else {//send email
        $email = $_REQUEST['email'];
        if (isset($_REQUEST['name'])) {
            $name = $_REQUEST['name'];
        } else {
            $name = "unknown";
        }
        
        $subject = "survey results";
        
        $q1 = $_REQUEST['q1'];
        $q2 = $_REQUEST['q2'];
        $q3 = $_REQUEST['q3'];
        $q4 = $_REQUEST['q4'];
        $q5 = $_REQUEST['q5'];
        $q6 = $_REQUEST['q6'];
        $q7 = $_REQUEST['q7'];
        $q8 = $_REQUEST['q8'];
        $q9 = $_REQUEST['q9'];
        $q10 = $_REQUEST['q10'];
        $results = "$name,$email,$q1,$q2,$q3,$q4,$q5,$q6,$q7,$q8,$q9,$q10,\n";
        
        mail(
            "receiving_email_address_goes_here", "Subject: $subject",
            $results, "From: $email" 
        );
        
        //write to CSV file as mail failure
        $fp = fopen("surveyResults/surveyResults.csv", 'a+');
        fwrite($fp, $results);
        fclose($fp);
        
        //echo "Thanks for filling in the survey!<br />";
        include "surveyResults.php";
    }
} else {
    //if "email" is not filled out, display the form
    include "includes/surveyMain.php"; 
}
?>
