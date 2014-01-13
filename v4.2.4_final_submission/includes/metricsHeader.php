<?php
/**
 * Header File containing the html and the form for the metrics engine
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
        <title>Goobling! Metrics</title> 
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
                                  method="POST" action="metrics.php">
                                <div class="myForm">
                                    <a name ="top"></a>
                                    <input id="metricsShow" 
                                           name="metrics" type="radio" 
                                           value="metricsShow" CHECKED/> 
                                    <label for="metricsShow">Current Metrics</label>

                                    <input id="metricsCalc" 
                                           name="metrics" type="radio" 
                                           value="metricsCalc" /> 
                                    <label for="metricsCalc">
                                        Re-Calculate Metrics
                                    </label>

                                    <input id="metricsGet" 
                                           name="metrics" type="radio" 
                                           value="metricsGet" />
                                    <label for="metricsGet">Get New Metrics</label>
                                </div>
                                
                                <label for="theMetrics">
                                    <h1><b>Goobling!</b></h1>
                                </label><br />
                                
                                <input class="btn btn-large btn-primary" 
                                       name="theMetrics" type="submit" 
                                       value="Metrics ON/OFF" /> 
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row-fluid" style="text-align:center">
                    <div class="span12">
