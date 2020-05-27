<?php

    require_once "header.php";
    //The variables to hold the responses to the survey, 4 of them are booleans for responses with the checkboxes
    $username = "";
    $resp1 = "";
    $resp2 = "";
    $resp3 = false;
    $resp4 = false;
    $resp5 = "";
    $resp6 = "";
    $resp7 = false;
    $resp8 = false;
    //Variables to hold error checks
    $username_errors = ""; 
    $resp1_errors = "";
    $resp2_errors = "";
    $resp3_errors = "";
    $resp4_errors = ""; 
    $resp5_errors = ""; 
    $resp6_errors = ""; 
    $resp7_errors = ""; 
    $resp8_errors = ""; 

    //user is already logged in 
    if(!isset($_SESSION['loggedInSkeleton']))
    {
        echo "You are already logged in, please log out first.<br>";
    }
    elseif(isset($_POST['resp1']))
    {
        //if response one has been posted do all this stuff

        //if reponse 3 has been posted set the boolean to true else set it to false, this code is repeated 4 times for all of the booleans in the static survey
        if (isset($_POST['resp3'])) 
        {
            $resp3 = true;
        }
        else 
        {
            $resp3 = false;
        }

        if (isset($_POST['resp4'])) 
        {
            $resp4 = true;
        }
        else 
        {
            $resp4 = false;
        }

        if (isset($_POST['resp7'])) 
        {
            $resp7 = true;
        }
        else 
        {
            $resp7 = false;
        }

        if (isset($_POST['resp8'])) 
        {
            $resp8 = true;
        }
        else 
        {
            $resp8 = false;
        }

        //set the username variable to the logged in username
        $username = $_SESSION['username'];
        //boiler plate connection and connection check code
        $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	    if (!$connection)
	    {
		    die("Connection failed: " . $mysqli_connect_error);
        }
        //sanitising all userresponses which are strings
        $resp1 = sanitise($_POST['resp1'],$connection);
        $resp2 = sanitise($_POST['resp2'],$connection);
        $resp5 = sanitise($_POST['resp5'],$connection);
        $resp6 = sanitise($_POST['resp6'],$connection);
        $username = $_SESSION['username'];

        //Validation for all strings
        $username_errors = validateString($username,1,16);
        $resp1_errors = validateString($resp1,1,32);
        $resp2_errors = validateString($resp2,1,32);
        $resp5_errors = validateDate($resp5);
        $resp6_errors = validateString($resp6,1,32);

        //inserting data from the forms into the staticsurvey table
        $query = "INSERT INTO staticsurvey (username,resp1,resp2,resp3,resp4,resp5,resp6,resp7,resp8) VALUES ('$username','$resp1','$resp2','$resp3','$resp4','$resp5','$resp6','$resp7','$resp8');";
        $result = mysqli_query($connection, $query);

     
    }
    //Echoing out the submission form not that the values for the checkbox and radio button are one if the button is clicked
    echo <<<_END
    <!-- As you can see the values are set to the posted variables with the exception of boolean values and there is error
    checking on all fields that don't contain boolean values-->
        <form action = "static_survey.php" method = "post">
            <br>
            Fill out this test survey if you want
            Whats your favourite colour <input type = "text" name = "resp1" value= "$resp1"> $resp1_errors
            <br>
            Tell us about yourself <input type = "text" name = "resp2" value= "$resp2"> $resp2_errors
            <br>
            Whats your gender
            <br>
            Male <input type = "checkbox" name = "resp3" value = "1"> 
            <br>
            Female <input type = "checkbox" name = "resp4" value = "1"> 
            <br>
            Whats your date of birth <input type = "date" name = "resp5" value= "$resp5"> $resp5_errors
            <br>
            Tell us your favourite animal <input type = "text" name = "resp6" value= "$resp6"> $resp6_errors
            <br>
            Which animal would win in a fight
            <br>
            Lion <input type = "radio" name = "resp7" value = "1"> 
            <br>
            Tiger <input type = "radio" name = "resp8" value = "1">
            <br>
            <input type="submit" name="Submit">
        </form>

_END;

require_once "footer.php";
?>