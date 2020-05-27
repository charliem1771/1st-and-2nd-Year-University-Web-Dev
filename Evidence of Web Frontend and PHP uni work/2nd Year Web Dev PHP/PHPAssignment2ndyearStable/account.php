<?php


//grabbing the header script
require_once "header.php";

//default variables to go in the form
$email = "";
$firstname = "";
$surname = "";
$telenum = "";
$DOB = "";
$password = "";
//strings to hold validation messages
$email_val = "";
$firstname_val = "";
$surname_val = "";
$telenum_val = "";
$DOB_val = "";
$password_val = "";

$show_account_form = false;

$message = "";
//checking if user is not logged in
if (!isset($_SESSION['loggedInSkeleton']))
{
	echo "You must be logged in to view this page.<br>";
}
//the post method checks for a inputted email in the form below if so attempt to update the profile
elseif (isset($_POST['email'])) 
{
	
	//connecting to the database
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	//checking for connection failure
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}
	
	//Using the saitisatiion functions in helper.php to clean all of the data inputted in the form
	$password = sanitise($_POST['password'], $connection);
    $email = sanitise($_POST['email'], $connection);
	$DOB = sanitise($_POST['DOB'], $connection);
	$telenum = sanitise($_POST['telenum'],$connection);
	$firstname = sanitise($_POST['firstname'],$connection);
	$surname = sanitise($_POST['surname'],$connection);

	//Using the validate functions to make sure all inputted form data is valid
	$password_val = validateString($password, 1, 16);
    $email_val = validateEmail($email);
	$DOB_val = validateDate($DOB);
	$telenum_val = validateString($telenum,1,24);
	$fistname_val = validateString($firstname,1,24);
	$surname_val = validateString($surname,1,24);
    
	//submitting all of the data which has been inputted
	$firstname = $_POST['firstname'];
	$email = $_POST['email'];
	$surname = $_POST['surname'];
	$telenum = $_POST['telenum'];
	$DOB = $_POST['DOB'];
	$password = $_POST['password'];
	
	$errors = "";

	//checking for any validation errors
	if ($errors == "")
	{		
		//read the username from the currently logged in session
		$username = $_SESSION["username"];
		
		//creating a variable storing a SQL query which grabs all data based in the users table based off the login
		$query = "SELECT * FROM users WHERE username='$username'";
		//processing the query
		$result = mysqli_query($connection, $query);
		
		//checking how many rows there are will only ever be 0 or 1 cos username is are primary key
		$n = mysqli_num_rows($result);
			
		//If there was a match for the result run the update
		if ($n > 0)
		{
			//A sql query to update all of the values based of the variables posted to the form
			$query = "UPDATE users SET email='$email',firstname ='$firstname',surname ='$surname',telenum = '$telenum',password = '$password',DOB = '$DOB' WHERE username='$username'";
			$result = mysqli_query($connection, $query);		
		}
	


		if ($result) 
		{
			//show a successful update message
			$message = "Profile successfully updated<br>";
		} 
		else
		{
			//show the set profile form
			$show_account_form = true;
			//show a failed update message
			$message = "Update failed<br>";
		}
	}
	else
	{
		//validation failed display form again with guidance 
		$show_account_form = true;
		$message = "Update failed, please check the errors above and try again<br>";
	}
	
	//close db connection
	mysqli_close($connection);

}
else
{
	//The code up until line 134 is mostly boiler plate and explained above in full detail
	$username = $_SESSION["username"];
	
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}
	
	$query = "SELECT * FROM users WHERE username='$username'";
	
	$result = mysqli_query($connection, $query);
	$n = mysqli_num_rows($result);
		
	if ($n > 0)
	{
		//grab the result of the query and store it in $row
		$row = mysqli_fetch_assoc($result);
		//grab all the users profile data and prepare it for use in HTML
		$email = $row['email'];
		$firstname = $row['firstname'];
		$surname = $row['surname'];
		$telenum = $row['telenum'];
        $DOB = $row['DOB'];
		$password = $row['password'];
	}
	
	$show_account_form = true;
	
	mysqli_close($connection);
}

if ($show_account_form)
{
echo <<<_END
<!-- The submission form used to submit the user data for updates, the username is grabbed as a session variable and is not editable.
The values of the other input forms are filled with user data we grabbed earlier these are editable, the validation variables are added to the 
end of each input form to make sure all server side data is valid.-->

<form action="account.php" method="post">
  Update your profile info:<br>
  Username: {$_SESSION['username']}
  <br>
  Email address: <input type="text" name="email" value="$email"> $email_val
  <br>
  First Name: <input type="text" name="firstname" value="$firstname"> $firstname_val
  <br>
  Surname: <input type="text" name="surname" value="$surname"> $surname_val
  <br>
  Telephone Number: <input type="text" name="telenum" value="$telenum"> $telenum_val
  <br>
  DOB: <input type="text" name="DOB" value="$DOB"> $DOB_val
  <br>
  Password: <input type="password" name="password" value="$password"> $password_val
  <input type="submit" value="Submit">
</form>	
_END;
}

// display our message to the user:
echo $message;

// finish of the HTML for this page:
require_once "footer.php";
?>