<?php
/**
 * Header File containing the html and the form
 * 
 * PHP version 5
 * 
 * @category PHP
 * @package  PHP_Metasearch_Engine
 * @author   Andrew Lamb <andrew.r.d.lamb@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://csserver.ucd.ie/~01421662/
 */
//if a query has been posted, assign it to a variable to maintain it's display
//in the query text box
if (isset($_POST['search'])) {
    $query = $_POST['query'];
} else if (isset($_POST['synonymSearch'])) {
    if (isset($_POST['alternative'])) {
        $query = $_POST['alternative'];
    } else {
        $query = '';
    }    
} else {
    $query = '';
}
?>
<!DOCTYPE html>
<html lang="en"> 
    <head> 
        <title>Goobling!</title> 
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
                            <form class="form-search" 
                                  method="POST" action="index.php">
                                <div class="myForm">
                                    <a name ="top"></a>
                                    <input id="result_type1" 
                                           name="result_type" type="radio" 
                                           value="Aggregated" CHECKED/> 
                                    <label for="result_type1">Aggregated</label>

                                    <input id="result_type2" 
                                           name="result_type" type="radio" 
                                           value="Non-Aggregated" /> 
                                    <label for="result_type2">Non-Aggregated</label>

                                    <input id="result_type3" 
                                           name="result_type" type="radio" 
                                           value="Weighted" />
                                    <label for="result_type3">Weighted</label>

                                    <input id="result_type4" 
                                           name="result_type" type="radio" 
                                           value="Clustered" /> 
                                    <label for="result_type4">Clustered</label>
                                </div>

                                <label for="query">
                                    <h1><b>Goobling!</b></h1>
                                </label><br />

                                <input class="input-large search-query" 
                                       style="width:260px" name="query" type="text"  
                                       placeholder='search...' 
                                       value="<?php print urldecode($query); ?>" />
                                <br /><br />

                                <label class="checkbox">
                                <input name="reWrite" type="checkbox" 
                                       value="reWrite" /> Suggest Words 
                                </label>
                                <br /><br />

                                <label class="radio" for="numResults">
                                    Number of Results:
                                </label><br />
                                
                                <label class="radio inline">
                                <input name="numResults" type="radio" 
                                       value="10" CHECKED /> 10 
                                </label>
                                
                                <label class="radio inline">
                                <input name="numResults" type="radio" 
                                       value="50" /> 50
                                </label>
                                
                                <label class="radio inline">
                                <input name="numResults" type="radio" 
                                       value="100" /> 100
                                </label><br /><br />

                                <input class="btn btn-large btn-primary" 
                                       name="search" type="submit" 
                                       value="Search" /> 
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
