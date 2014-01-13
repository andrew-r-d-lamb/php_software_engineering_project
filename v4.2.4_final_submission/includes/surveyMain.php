<!DOCTYPE html>
<html lang="en"> 
    <head> 
        <title>Goobling! survey</title> 
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
                            <h2>Survey</h2>
                            <p><b>Guide:</b><br />
                                <ul class="inline">
                                    <li>1: Strongly Disagree</li>
                                    <li>2: Disagree</li>
                                    <li>3: Undecided</li>
                                    <li>4: Agree</li>
                                    <li>5: Strongly Agree</li>
                                </ul>
                            </p>
                            <br />
                            <form method="POST" action="survey.php">
                                <p><b>1.</b> The quality of the results was superior 
                                    to my normal search engine<br />
                                    
                                    <label class="radio inline">
                                        <input id="1.1" name="q1" 
                                               type="radio" value="1" /> 1
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="1.2" name="q1" 
                                               type="radio" value="2" /> 2
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="1.3" name="q1" 
                                               type="radio" value="3" CHECKED/> 3
                                    </label> 
                                    
                                    <label class="radio inline">
                                        <input id="1.4" name="q1" 
                                               type="radio" value="4" /> 4
                                    </label>  
                                    
                                    <label class="radio inline">
                                        <input id="1.5" name="q1" 
                                               type="radio" value="5" /> 5
                                    </label> 
                                </p>
                                <br />
                                
                                <p><b>2.</b> The quality of the aggregated results 
                                    was superior to the non-aggregated results<br />
                                    
                                    <label class="radio inline">
                                        <input id="2.1" name="q2" 
                                               type="radio" value="1" /> 1
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="2.2" name="q2" 
                                               type="radio" value="2" /> 2
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="2.3" name="q2" 
                                               type="radio" value="3" CHECKED/> 3
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="2.4" name="q2" 
                                               type="radio" value="4" /> 4
                                    </label>  
                                    
                                    <label class="radio inline">
                                        <input id="2.5" name="q2" 
                                               type="radio" value="5" /> 5
                                    </label>
                                </p> 
                                <br />
                                
                                <p><b>3.</b> The quality of the weighted results 
                                    was superior to the aggregated results<br />
                                    
                                    <label class="radio inline">
                                        <input id="3.1" name="q3" 
                                               type="radio" value="1" /> 1
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="3.2" name="q3" 
                                               type="radio" value="2" /> 2
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="3.3" name="q3" 
                                               type="radio" value="3" CHECKED/> 3
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="3.4" name="q3" 
                                               type="radio" value="4" /> 4
                                    </label>  
                                    
                                    <label class="radio inline">
                                        <input id="3.5" name="q3" 
                                               type="radio" value="5" /> 5
                                    </label>
                                </p>             
                                <br />
                                
                                <p><b>4.</b> I found it useful when the results 
                                    were clustered<br />
                                    
                                    <label class="radio inline">
                                        <input id="4.1" name="q4" 
                                               type="radio" value="1" /> 1
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="4.2" name="q4" 
                                               type="radio" value="2" /> 2
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="4.3" name="q4" 
                                               type="radio" value="3" CHECKED/> 3
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="4.4" name="q4" 
                                               type="radio" value="4" /> 4
                                    </label>  
                                    
                                    <label class="radio inline">
                                        <input id="4.5" name="q4" 
                                               type="radio" value="5" /> 5
                                    </label>
                                </p>         
                                <br />
                                
                                <p><b>5.</b> The function for suggesting 
                                    alternative words was useful<br />
                                    
                                    <label class="radio inline">
                                        <input id="5.1" name="q5" 
                                               type="radio" value="1" /> 1
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="5.2" name="q5" 
                                               type="radio" value="2" /> 2
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="5.3" name="q5" 
                                               type="radio" value="3" CHECKED/> 3
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="5.4" name="q5" 
                                               type="radio" value="4" /> 4
                                    </label>  
                                    
                                    <label class="radio inline">
                                        <input id="5.5" name="q5" 
                                               type="radio" value="5" /> 5
                                    </label>
                                </p>    
                                <br />
                                
                                <p><b>6.</b> I found the interface very 
                                    easy to use<br />
                                    
                                    <label class="radio inline">
                                        <input id="6.1" name="q6" 
                                               type="radio" value="1" /> 1
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="6.2" name="q6" 
                                               type="radio" value="2" /> 2
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="6.3" name="q6" 
                                               type="radio" value="3" CHECKED/> 3
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="6.4" name="q6" 
                                               type="radio" value="4" /> 4
                                    </label>  
                                    
                                    <label class="radio inline">
                                        <input id="6.5" name="q6" 
                                               type="radio" value="5" /> 5
                                    </label>
                                </p>
                                <br />
                                
                                <p><b>7.</b> I liked how the results 
                                    were presented<br />
                                    
                                    <label class="radio inline">
                                        <input id="7.1" name="q7" 
                                               type="radio" value="1" /> 1
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="7.2" name="q7" 
                                               type="radio" value="2" /> 2
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="7.3" name="q7" type="radio" 
                                               value="3" CHECKED/> 3
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="7.4" name="q7" 
                                               type="radio" value="4" /> 4
                                    </label>  
                                    
                                    <label class="radio inline">
                                        <input id="7.5" name="q7" 
                                               type="radio" value="5" /> 5
                                    </label>
                                </p>
                                <br />
                                
                                <p><b>8.</b> I liked being given the choice of 
                                    the number of results to search for<br />
                                    
                                    <label class="radio inline">
                                        <input id="8.1" name="q8" 
                                               type="radio" value="1" /> 1
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="8.2" name="q8" 
                                               type="radio" value="2" /> 2
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="8.3" name="q8" 
                                               type="radio" value="3" CHECKED/> 3
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="8.4" name="q8" 
                                               type="radio" value="4" /> 4
                                    </label>  
                                    
                                    <label class="radio inline">
                                        <input id="8.5" name="q8" 
                                               type="radio" value="5" /> 5
                                    </label>
                                </p>
                                <br />
                                
                                <p><b>9.</b> The speed of the Goobling is 
                                    comparable to my normal search engine<br />
                                    
                                    <label class="radio inline">
                                        <input id="9.1" name="q9" 
                                               type="radio" value="1" /> 1
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="9.2" name="q9" 
                                               type="radio" value="2" /> 2
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="9.3" name="q9" 
                                               type="radio" value="3" CHECKED/> 3
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="9.4" name="q9" 
                                               type="radio" value="4" /> 4
                                    </label>  
                                    
                                    <label class="radio inline">
                                        <input id="9.5" name="q9" 
                                               type="radio" value="5" /> 5
                                    </label>
                                </p>
                                <br />
                                
                                <p><b>10.</b> I would consider making this my 
                                    default search engine<br />
                                    
                                    <label class="radio inline">
                                        <input id="10.1" name="q10" 
                                               type="radio" value="1" /> 1
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="10.2" name="q10" 
                                               type="radio" value="2" /> 2
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="10.3" name="q10" 
                                               type="radio" value="3" CHECKED/> 3
                                    </label>
                                    
                                    <label class="radio inline">
                                        <input id="10.4" name="q10" 
                                               type="radio" value="4" /> 4
                                    </label>  
                                    
                                    <label class="radio inline">
                                        <input id="10.5" name="q10" 
                                               type="radio" value="5" /> 5
                                    </label>
                                </p>
                                <br />
                                <label for="name">Please enter your name:</label>
                                <input name="name" type="text" value="" />
                                
                                <label for="email">
                                    Please enter your email address:
                                </label>                                
                                <input name="email" type="text" value="" />
                                <br />
                                <input class="btn btn-large btn-success" 
                                       name="survey" type="submit" 
                                       value="submit" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="push">
        </div>
        <div class="footer">    
            <div class="container">
                <p class="text-center">&copy; Andrew Lamb 2013<br /> 
                    <a class="text-center" href="mailto:Andrew.Lamb@ucdconnect.ie">
                        email</a><br />
                    <a class="btn btn-inverse" href="index.php">Back To Goobling!</a>
                </p>
            </div>
        </div>
    </body>
</html>
