<?php

//sanitise function just cleans user data
function sanitise($str, $connection)
{
	if (get_magic_quotes_gpc())
	{
		//Incase sever is running a old PHP version with magic quotes
		$str = stripslashes($str);
	}
	//Escape any dangerous characters
	$str = mysqli_real_escape_string($connection, $str);
	//Secure html code by converting it to entities
	$str = htmlentities($str);
	//return the string
	return $str;
}

//Validation for any strings sent to the database
function validateString($field, $minlength, $maxlength) 
{
    if (strlen($field)<$minlength) 
    {
		//Strings minimum length is invalid		
        return "Minimum length: " . $minlength; 
    }
	elseif (strlen($field)>$maxlength) 
    { 
		//Strings maximum length is invalid
        return "Maximum length: " . $maxlength; 
    }
	//Return the string if its valid
    return ""; 
}

//Validation check for any integers sent to the database 
function validateInt($field, $min, $max) 
{ 
	// see PHP manual for more info on the options: http://php.net/manual/en/function.filter-var.php, Skeleton code comment leaving it here
	$options = array("options" => array("min_range"=>$min,"max_range"=>$max));
    
	if (!filter_var($field, FILTER_VALIDATE_INT, $options)) 
    { 
		//Checking if the paseed integer is valid if it's not return a help message
        return "Not a valid number (must be whole and in the range: " . $min . " to " . $max . ")"; 
    }
	//
    return ""; 
}

//The function for date validation
function validateDate($field)
{
    //Putting the parsed date into a array so we can grab the day,month and year
    $date = date_parse($field);

    //using PHP's checkdate() to make sure the date is valid, if statement has standard valid and invalid checks
    if (checkdate($date['month'], $date['day'], $date['year'])) 
	{
        //return nothing if date is valid
        return "";
    }
    else 
	{
        return "Date is not valid";
    }

}

//validate Email function
function validateEmail($field)
{
    //Removing all illegals characters from the string
    $field = filter_var($field, FILTER_SANITIZE_EMAIL);

    //Making sure the emeail address matches the expected format
    if (filter_var($field, FILTER_VALIDATE_EMAIL)) 
	{
        return "";
    }
    else 
	{
        return "Email address is not valid ";
    }

}

?>