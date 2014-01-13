<?php
/**
 * Program to display current survey results - with invalid email message
 * 
 * PHP version 5
 * 
 * @category PHP
 * @package  PHP_Metasearch_Engine
 * @author   Andrew Lamb <andrew.r.d.lamb@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://csserver.ucd.ie/~01421662/
 */
?>
<!DOCTYPE html>
<html lang="en"> 
    <head> 
        <title>Goobling! survey results</title> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <!-- Bootstrap -->
        <link href="assets/css/bootstrap.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding-top: 10px;
                padding-bottom: 40px;
            }
        </style>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
        
        <!-- Radio Buttons As Buttons -->
        <link href="includes/myCSS.css" rel="stylesheet">
        
        <style type="text/css">
            /*---------------------------------------------------------------------
                BOOTSRTAP STICKY FOOTER
            --------------------------------------------------------------------- */
            html,
            body {
              height: 100%;
              /* The html and body elements cannot have any padding or margin. */
            }

            /* Wrapper for page content to push down footer */
            #wrap {
              min-height: 90%;
              height: auto !important;
              height: 90%;
              /* Negative indent footer by it's height */
              margin: 0 auto -60px;
            }

            /* Set the fixed height of the footer here */
            #push,
            #footer {
              height: 60px;
            }
            #footer {
              background-color: #f5f5f5;
            }

            /* Lastly, apply responsive CSS fixes as necessary */
            @media (max-width: 767px) {
              #footer {
                margin-left: -20px;
                margin-right: -20px;
                padding-left: 20px;
                padding-right: 20px;
              }
            }
            
        </style>

    </head>
    <body> 
        <div id="wrap">
            <div class="container-fluid">
                <div class="row-fluid" style="text-align:center">
                    <div class="span12">
                        <div class="span12">
                            <h1><b>Goobling!</b></h1>
                            <h2>Current Survey Results</h2>
                        </div>
                    </div>
                </div>
<?php
/**
 * Function to read contents of surveyResults file and display
 * 
 * @return void
 */
function displaySurveyResults()
{
    $resultsStr = file_get_contents('surveyResults/surveyResults.csv');
    
    //convert to array by newline character
    $resultsArr = explode("\n", $resultsStr);
    
    //convert individual values to arrays by ","
    foreach ($resultsArr as $key => &$value) {
        $value = explode(",", $value);
    }
    
    //get length of array
    $arrayLength = count($resultsArr);

    //initialize array to hold scores
    $scores = array();
    for ($i=0; $i<10; $i++) {
        $scores[$i] = "";
    }
    
    //calculate totals
    for ($i=0; $i<10; $i++) {
        for ($j=1; $j<$arrayLength; $j++) {
            if (isset($scores[$i]) && isset($resultsArr[$j][$i+2])) {
                $scores[$i] = $scores[$i] + $resultsArr[$j][$i+2];
            }
        }
    }
        
    //calculate average
    for ($i=0; $i<10; $i++) {
        $scores[$i] = $scores[$i] / ($arrayLength-2); //-2 (1st titles,last empty)
    }
    
    //display
    echo "<div class=\"row-fluid\">";
    echo "<div class=\"span7\" style=\"text-align:right\">";    
    for ($i=2; $i<12; $i++) {
        echo $resultsArr[0][$i] . "<br />"; 
            //. number_format($scores[$i-2], 2, '.', '') . "<br />";
    }
    echo "</div>";
    echo "<div class=\"span5\" style=\"text-align:left\">";    
    for ($i=2; $i<12; $i++) {
        echo //$resultsArr[0][$i] . " " 
            number_format($scores[$i-2], 2, '.', '') . "<br />";
    }
    echo "</div>";
    echo "</div>";
    
    echo "<div class=\"row-fluid\">";
    echo "<div class=\"span12\" style=\"text-align:center\">";
    echo "<h3 class=\"text-warning\">Invalid email address entered, 
        please try again</h3>";
    echo "</div>";
    echo "</div>";
    
}
displaySurveyResults();          
?>
                        
                    
            </div>
        </div>
        <div id="push">
        </div>
        <div class="footer">    
            <div class="container">
                <p class="text-center">&copy; Andrew Lamb 2013<br /> 
                    <a class="text-center" href="mailto:Andrew.Lamb@ucdconnect.ie">
                        email</a><br />
                    <a class="btn btn-danger" href="survey.php">Back To Survey!</a>
                </p>
            </div>
        </div>
    </body>
</html>
