<?php


// execute the header script:
require_once "header.php";
//variables to hold the sign up data
$username = "";
$password = "";
$email = "";
$DOB = "";
$telenum = "";
$firstname = "";
$surname = "";
//variables to hold the validation checks
$username_val = "";
$password_val = "";
$email_val = "";
$DOB_val = "";
$telenum_val = "";
$firstname_val = "";
$surname_val = "";

$show_signup_form = false;

$message = "";

//checking is user is already logged in
if (isset($_SESSION['loggedInSkeleton']))
{
	echo "You are already logged in, please log out if you wish to create a new account<br>";
}
elseif (isset($_POST['username']))
{
	//user tried to sign up

	//boiler plate connection checks
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}	
	
	
	//sanitising all posted values 
	$username = sanitise($_POST['username'], $connection);
	$password = sanitise($_POST['password'], $connection);
    $email = sanitise($_POST['email'], $connection);
	$DOB = sanitise($_POST['DOB'], $connection);
	$telenum = sanitise($_POST['telenum'],$connection);
	$firstname = sanitise($_POST['firstname'],$connection);
	$surname = sanitise($_POST['surname'],$connection);

	//checking all of the values submittted are valid 
	$username_val = validateString($username, 1, 16);
	$password_val = validateString($password, 1, 16);
    $email_val = validateEmail($email);
	$DOB_val = validateDate($DOB);
	$telenum_val = validateString($telenum,1,24);
	$fistname_val = validateString($firstname,1,24);
	$surname_val = validateString($surname,1,24);

	$errors = $username_val . $password_val . $email_val . $DOB_val . $telenum_val . $fistname_val . $surname_val;
	
	// check that all the validation tests passed before going to the database:
	if ($errors == "")
	{
		
		//creating a sql query to insert new data into the database
		$query = "INSERT INTO users (username,password,email,DOB,telenum,firstname,surname) VALUES ('$username', '$password', '$email','$DOB','$telenum','$firstname','$surname');";
		$result = mysqli_query($connection, $query);

		//checking for failure or success
		if ($result) 
		{
			$message = "Signup was successful, please sign in<br>";
		} 
		else 
		{
			$show_signup_form = true;
			$message = "Sign up failed, please try again<br>";
		}
			
	}
	else
	{
		//validation has failed show the form again with appropiate guidance
		$show_signup_form = true;
		$message = "Sign up failed, please check the errors shown above and try again<br>";
	}
	mysqli_close($connection);
}
else
{
	$show_signup_form = true;
	
}

if ($show_signup_form)
{
//The sign up form with standard validation checks	
echo <<<_END
<form action="sign_up.php" method="post">
  Please choose a username and password:<br>
  Username: <input type="text" name="username" maxlength="16" value="$username" required> $username_val
  <br>
  Password: <input type="password" name="password" maxlength="16" value="$password" required> $password_val
  <br>
  Email: <input type="email" name="email" maxlength="64" value="$email" required> $email_val
  <br>
  DOB: <input type = "date" name = "DOB" maxlength = "16" value = "$DOB" required> $DOB_val
  <br>
  Telephone: <input type = "text" name = "telenum" maxlength = "11" value = "$telenum" required> $telenum_val
  <br>
  First Name: <input type = "text" name = "firstname" maxlength = "24" value = "$firstname" required> $firstname_val
  <br>
  Last Name: <input type = "text" name = "surname" maxlength = "24" value = "$surname" required> $surname_val
  <input type="submit" value="Submit">
</form>	
_END;
}

// display our message to the user:
echo $message;

// finish off the HTML for this page:
require_once "footer.php";

?>