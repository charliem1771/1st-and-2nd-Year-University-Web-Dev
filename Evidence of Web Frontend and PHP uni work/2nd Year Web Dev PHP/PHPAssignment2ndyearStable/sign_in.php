<?php


require_once "header.php";

//default variables to show in the signin form
$username = "";
$password = "";
//variable to store validation checks
$username_val = "";
$password_val = "";

$show_signin_form = false;
$message = "";

//if there is a input in the username field try and login
if (isset($_POST['username']))
{	
	//Boiler plate code to connect to the database
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}	
	
	//sanitising the data inputted from the form
	$username = sanitise($_POST['username'], $connection);
	$password = sanitise($_POST['password'], $connection);
	
	//Making sure the strings are valid
	$username_val = validateString($username, 1, 16);
	$password_val = validateString($password, 1, 16);
	

	$errors = $username_val . $password_val;
	if ($errors == "")
	{
		
		if (isset($_POST['username']) || $username == 'admin')
		{
			//checking is a username has been submitted or if admin has been submitted
			$username = $_POST['username'];
			$password = $_POST['password'];
			if (!$connection)
			{
				die("Connection failed: " . $mysqli_connect_error);
			}
			//checking if the user name and password match the db
			$query = "SELECT username FROM users WHERE username='$username' AND password='$password'";
			$result = mysqli_query($connection, $query);
			$n = mysqli_num_rows($result);	
		}
		else
		{
			$n = 0;
		}
			
		//if  a match is found login
		if ($n > 0)
		{
			//setting the login session to true
			$_SESSION['loggedInSkeleton'] = true;
			//grabbing the username for use in other scripts
			$_SESSION['username'] = $username;
			
			//disply a succesfull sign in message
			$message = "Hi, $username, you have successfully logged in, please <a href='account.php'>click here</a><br>";
		}
		else
		{
			// no matching credentials found so redisplay the signin form with a failure message:
			$show_signin_form = true;
			// show an unsuccessful signin message:
			$message = "Sign in failed, please try again<br>";
		}
		
	}
	else
	{
		// validation failed, show the form again with guidance:
		$show_signin_form = true;
		// show an unsuccessful signin message:
		$message = "Sign in failed, please check the errors shown above and try again<br>";
	}
	// we're finished with the database, close the connection:
	mysqli_close($connection);

}
else
{
	// user has arrived at the page for the first time, just show them the form:	
	// show signin form:
	$show_signin_form = true;
}

if ($show_signin_form)
{
// show the form that allows users to log in
// Note we use an HTTP POST request to avoid their password appearing in the URL:
echo <<<_END
<form action="sign_in.php" method="post">
  Please enter your username and password:<br>
  Username: <input type="text" name="username" maxlength="16" value="$username" required> $username_val
  <br>
  Password: <input type="password" name="password" maxlength="16" value="$password" required> $password_val
  <br>
  <input type="submit" value="Submit">
</form>	
_END;
}

// display our message to the user:
echo $message;

// finish off the HTML for this page:
require_once "footer.php";
?>