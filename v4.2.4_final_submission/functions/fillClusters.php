<?php
/**
 * Function to sort results into clusters 
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
 * Function to sort results into clusters
 * 
 * @param array &$aggregatedArray array with all info including coordinates
 * @param array &$clusterA        array to store first cluster
 * @param array &$clusterB        array to store second cluster
 * @param array &$clusterC        array to store third cluster
 * @param array &$clusterD        array to store fourth cluster
 * 
 * @return null
 */
function fillClusters(
    &$aggregatedArray, &$clusterA
    , &$clusterB, &$clusterC, &$clusterD
) {
    //allocate initial centroids and placeholders for old centroids
    $centroidA = explode(",", $aggregatedArray[0][6]);
    $centroidB = explode(",", $aggregatedArray[3][6]);
    $centroidC = explode(",", $aggregatedArray[8][6]);
    $centroidD = explode(",", $aggregatedArray[10][6]);
    $oldCentroidA = array(); //plan to use as a loop stopping condition
    $oldCentroidB = array(); //plan to use as a loop stopping condition
    $oldCentroidC = array(); //plan to use as a loop stopping condition
    $oldCentroidD = array(); //plan to use as a loop stopping condition
    
    //get length of a vector (-1 to block using the last element which is empty)
    $centroidLength = count($centroidA) - 1; 
    
    //initialize temporary variables to use for calculating cosine similarity
    //versus centroidA
    $productA = 0; //stores the product of 2 elements
    $dotProductA = 0; //stores the dot product of each product (sum of products)
    $vectorLengthA1 = 0; //stores the length of first vector
    $vectorLengthA2 = 0; //stores the length of second vector
    $vectorLengthAFinal = 0; //stores the final value to be divided by
    $cosSimilarityA = 0; //stores the value for COS similarity to centroidA
    //versus centroidB
    $productB = 0; //stores the product of 2 elements
    $dotProductB = 0; //stores the dot product of each product (sum of products)
    $vectorLengthB1 = 0; //stores the length of first vector
    $vectorLengthB2 = 0; //stores the length of second vector    
    $vectorLengthBFinal = 0; //stores the final value to be divided by
    $cosSimilarityB = 0; //stores the value for COS similarity to centroidB
    //versus centroidC
    $productC = 0; //stores the product of 2 elements
    $dotProductC = 0; //stores the dot product of each product (sum of products)
    $vectorLengthC1 = 0; //stores the length of first vector
    $vectorLengthC2 = 0; //stores the length of second vector   
    $vectorLengthCFinal = 0; //stores the final value to be divided by 
    $cosSimilarityC = 0; //stores the value for COS similarity to centroidC
    //versus centroidD
    $productD = 0; //stores the product of 2 elements
    $dotProductD = 0; //stores the dot product of each product (sum of products)
    $vectorLengthD1 = 0; //stores the length of first vector
    $vectorLengthD2 = 0; //stores the length of second vector  
    $vectorLengthDFinal = 0; //stores the final value to be divided by
    $cosSimilarityD = 0; //stores the value for COS similarity to centroidD
    
    $coordinateArray = array(); //store temporary array of coordinate

    //******************************************************************************
    //LOOP THE FOLLOWING CODE X TIMES OR UNTIL CONVERGENCE  
    //
    //******************************************************************************
    for ($x=0; $x<10; $x++) {
        //additional stopping condition if centroids haven't changed since
        //last iteration break loop
        if (($centroidA === $oldCentroidA)
            && ($centroidB === $oldCentroidB)
            && ($centroidC === $oldCentroidC)
            && ($centroidD === $oldCentroidD)
        ) {
            //echo "<br /><br />";
            //echo ($x+1) . "nth iteration <br />";
            //echo "Ending Loop: " 
            //. "Centroids haven't changed since last iteration <br />";
            break;
        }

        //reset cluster arrays
        $clusterA = array();
        $clusterB = array();
        $clusterC = array();
        $clusterD = array();    

        //loop through aggregated results, compare to centroids, 
        //assign to cluster based on which centroid it is closest to.
        foreach ($aggregatedArray as $key => $value) {
            $coordinateArray = explode(",", $value[6]);
            for ($i=0; $i<$centroidLength; $i++) {
                //get product of each element of each centroid 
                //and corresponding element of the coordinate array
                $productA = $centroidA[$i] * $coordinateArray[$i];
                $productB = $centroidB[$i] * $coordinateArray[$i];
                $productC = $centroidC[$i] * $coordinateArray[$i];
                $productD = $centroidD[$i] * $coordinateArray[$i];

                //calculate dot products
                $dotProductA = $dotProductA + $productA;
                $dotProductB = $dotProductB + $productB;
                $dotProductC = $dotProductC + $productC;
                $dotProductD = $dotProductD + $productD;

                //first part of vector length calculation
                $vectorLengthA1 
                    = $vectorLengthA1 + ($centroidA[$i] * $centroidA[$i]);
                $vectorLengthA2 
                    = $vectorLengthA2 
                    + ($coordinateArray[$i] * $coordinateArray[$i]);

                $vectorLengthB1 
                    = $vectorLengthB1 
                    + ($centroidB[$i] * $centroidB[$i]);
                $vectorLengthB2 
                    = $vectorLengthB2 
                    + ($coordinateArray[$i] * $coordinateArray[$i]);

                $vectorLengthC1 
                    = $vectorLengthC1 
                    + ($centroidC[$i] * $centroidC[$i]);
                $vectorLengthC2 
                    = $vectorLengthC2 
                    + ($coordinateArray[$i] * $coordinateArray[$i]);

                $vectorLengthD1 
                    = $vectorLengthD1 
                    + ($centroidD[$i] * $centroidD[$i]);
                $vectorLengthD2 
                    = $vectorLengthD2 
                    + ($coordinateArray[$i] * $coordinateArray[$i]);
            }
            //second part of vector length calculation
            $vectorLengthA1 = sqrt($vectorLengthA1);
            $vectorLengthA2 = sqrt($vectorLengthA2);
            $vectorLengthAFinal = $vectorLengthA1 * $vectorLengthA2;
            //prevent division by 0
            if ($vectorLengthAFinal == 0) {
                $vectorLengthAFinal = 1;
            }

            $vectorLengthB1 = sqrt($vectorLengthB1);
            $vectorLengthB2 = sqrt($vectorLengthB2);
            $vectorLengthBFinal = $vectorLengthB1 * $vectorLengthB2;
            //prevent division by 0
            if ($vectorLengthBFinal == 0) {
                $vectorLengthBFinal = 1;
            }

            $vectorLengthC1 = sqrt($vectorLengthC1);
            $vectorLengthC2 = sqrt($vectorLengthC2);
            $vectorLengthCFinal = $vectorLengthC1 * $vectorLengthC2;
            //prevent division by 0
            if ($vectorLengthCFinal == 0) {
                $vectorLengthCFinal = 1;
            }

            $vectorLengthD1 = sqrt($vectorLengthD1);
            $vectorLengthD2 = sqrt($vectorLengthD2);
            $vectorLengthDFinal = $vectorLengthD1 * $vectorLengthD2;
            //prevent division by 0
            if ($vectorLengthDFinal == 0) {
                $vectorLengthDFinal = 1;
            }

            //calculate COS similarity between vector and each centroid
            $cosSimilarityA = $dotProductA / $vectorLengthAFinal;
            $cosSimilarityB = $dotProductB / $vectorLengthBFinal;
            $cosSimilarityC = $dotProductC / $vectorLengthCFinal;
            $cosSimilarityD = $dotProductD / $vectorLengthDFinal;

            //Find which cluster to store snippet in
            //find number closest to 1 (should be highest number)
            //get highest number
            $maxCos = max(
                $cosSimilarityA, $cosSimilarityB, $cosSimilarityC, $cosSimilarityD
            );
            
            //compare back with each of the variables 
            //and put into corresponding cluster
            if ($cosSimilarityA === $maxCos) {
                $clusterA[] = array(
                    $value[0], $value[1], $value[2], $value[3],
                    $value[4], $value[5], $value[6]);
            } else if ($cosSimilarityB === $maxCos) {
                $clusterB[] = array(
                    $value[0], $value[1], $value[2], $value[3],
                    $value[4], $value[5], $value[6]);
            } else if ($cosSimilarityC === $maxCos) {
                $clusterC[] = array(
                    $value[0], $value[1], $value[2], $value[3],
                    $value[4], $value[5], $value[6]);
            } else {
                $clusterD[] = array(
                    $value[0], $value[1], $value[2], $value[3],
                    $value[4], $value[5], $value[6]);
            }

            //**********************************************************************
            //RESET VALUES FOR NEXT ITERATION
            //versus centroidA
            $productA = 0; //stores the product of 2 elements
            $dotProductA = 0; //stores dot product of each product (sum of products)
            $vectorLengthA1 = 0; //stores the length of first vector
            $vectorLengthA2 = 0; //stores the length of second vector
            $vectorLengthAFinal = 0; //stores the final value to be divided by
            $cosSimilarityA = 0; //stores the value for COS similarity to centroidA
            //versus centroidB
            $productB = 0; //stores the product of 2 elements
            $dotProductB = 0; //stores dot product of each product (sum of products)
            $vectorLengthB1 = 0; //stores the length of first vector
            $vectorLengthB2 = 0; //stores the length of second vector    
            $vectorLengthBFinal = 0; //stores the final value to be divided by
            $cosSimilarityB = 0; //stores the value for COS similarity to centroidB
            //versus centroidC
            $productC = 0; //stores the product of 2 elements
            $dotProductC = 0; //stores dot product of each product (sum of products)
            $vectorLengthC1 = 0; //stores the length of first vector
            $vectorLengthC2 = 0; //stores the length of second vector   
            $vectorLengthCFinal = 0; //stores the final value to be divided by 
            $cosSimilarityC = 0; //stores the value for COS similarity to centroidC
            //versus centroidD
            $productD = 0; //stores the product of 2 elements
            $dotProductD = 0; //stores dot product of each product (sum of products)
            $vectorLengthD1 = 0; //stores the length of first vector
            $vectorLengthD2 = 0; //stores the length of second vector  
            $vectorLengthDFinal = 0; //stores the final value to be divided by
            $cosSimilarityD = 0; //stores the value for COS similarity to centroidD

            $coordinateArray = array(); //store temporary array of coordinate
            //**********************************************************************
        }

        //Get length of each cluster (number of members of each cluster)
        $lengthClusterA = count($clusterA);
        $lengthClusterB = count($clusterB);
        $lengthClusterC = count($clusterC);
        $lengthClusterD = count($clusterD);

        //CALCULATE NEW CENTROIDS***************************************************
        //create a temp centroid array
        //prepare array
        $centroidArr = array();
        for ($i=0; $i<=$centroidLength; $i++) {
            $centroidArr[] = 0.0;
        }
        $coordinateExploded = array();

        //Get new centroid for cluster A
        //Loop to sum the values of the vectors
        for ($i=0; $i<$lengthClusterA; $i++) {
            $coordinateExploded = explode(",", $clusterA[$i][6]);
            for ($j=0; $j<=$centroidLength; $j++) {
                $centroidArr[$j] = $centroidArr[$j] + $coordinateExploded[$j];
            }
            //reset coordinateExploded array
            $coordinateExploded = array();
        }
        
        //Loop through new centroid array and calculate the average
        foreach ($centroidArr as &$value) {
            $value = $value/$lengthClusterA;
        }
        
        //copy centroid into old centroid variable
        //and copy new centroid into centroid variable
        $oldCentroidA = $centroidA;
        $centroidA = $centroidArr;

        //reset temp centroid array
        $centroidArr = array();
        for ($i=0; $i<=$centroidLength; $i++) {
            $centroidArr[] = 0.0;
        }   

        //Get new centroid for cluster B
        //Loop to sum the values of the vectors
        for ($i=0; $i<$lengthClusterB; $i++) {
            $coordinateExploded = explode(",", $clusterB[$i][6]);
            for ($j=0; $j<=$centroidLength; $j++) {
                $centroidArr[$j] = $centroidArr[$j] + $coordinateExploded[$j];
            }
            //reset coordinateExploded array
            $coordinateExploded = array();
        }
        //Loop through new centroid array and calculate the average
        foreach ($centroidArr as &$value) {
            $value = $value/$lengthClusterB;
        }
        
        //copy centroid into old centroid variable
        //and copy new centroid into centroid variable
        $oldCentroidB = $centroidB;
        $centroidB = $centroidArr;   

        //reset temp centroid array
        $centroidArr = array();
        for ($i=0; $i<=$centroidLength; $i++) {
            $centroidArr[] = 0.0;
        }   

        //Get new centroid for cluster C
        //Loop to sum the values of the vectors
        for ($i=0; $i<$lengthClusterC; $i++) {
            $coordinateExploded = explode(",", $clusterC[$i][6]);
            for ($j=0; $j<=$centroidLength; $j++) {
                $centroidArr[$j] = $centroidArr[$j] + $coordinateExploded[$j];
            }
            //reset coordinateExploded array
            $coordinateExploded = array();
        }
        //Loop through new centroid array and calculate the average
        foreach ($centroidArr as &$value) {
            //echo $value . "<br />";
            $value = $value/$lengthClusterC;
            //echo $value . "<br />";
        }
        
        //copy centroid into old centroid variable
        //and copy new centroid into centroid variable
        $oldCentroidC = $centroidC;
        $centroidC = $centroidArr;   

        //reset temp centroid array
        $centroidArr = array();
        for ($i=0; $i<=$centroidLength; $i++) {
            $centroidArr[] = 0.0;
        }   

        //Get new centroid for cluster D
        //Loop to sum the values of the vectors
        for ($i=0; $i<$lengthClusterD; $i++) {
            $coordinateExploded = explode(",", $clusterD[$i][6]);
            for ($j=0; $j<=$centroidLength; $j++) {
                $centroidArr[$j] = $centroidArr[$j] + $coordinateExploded[$j];
            }
            //reset coordinateExploded array
            $coordinateExploded = array();
        }
        //Loop through new centroid array and calculate the average
        foreach ($centroidArr as &$value) {
            $value = $value/$lengthClusterD;
        }
        
        //copy centroid into old centroid variable
        //and copy new centroid into centroid variable
        $oldCentroidD = $centroidD;
        $centroidD = $centroidArr;    

        //reset temp centroid array
        $centroidArr = array();
        for ($i=0; $i<=$centroidLength; $i++) {
            $centroidArr[] = 0.0;
        }       
    }
    //******************************************************************************
}
?>
