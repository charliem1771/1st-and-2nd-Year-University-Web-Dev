<?php

require_once "credentials.php";

//Grabbing the helper functions in the header so they don't need to be grabbed every time
require_once "helper.php";

//starting the session
session_start();

if (isset($_SESSION['loggedInSkeleton']))
{
//Echoing out a menu in HTML
echo <<<_END
<!DOCTYPE html>
<html>
<head><title>A Survey Website</title></head>
<body>
<a href='account.php'>My Account</a> ||
<a href='competitors.php'>Design and Analysis</a> ||
<a href='static_survey.php'>Static Survey</a> ||
<a href='display_results.php'>Survey Results</a> ||
<a href='sign_out.php'>Sign Out ({$_SESSION['username']})</a>
_END;
	//If the user is admin add a admin tools option
	if ($_SESSION['username'] == "admin")
	{
		echo " |||| <a href='admin.php'>Admin Tools</a>";
	}
}
else
{
//If the user isn't logged in display logged out menu
echo <<<_END
<!DOCTYPE html>
<html>
<body>
<a href='sign_up.php'>Sign Up</a> ||
<a href='sign_in.php'>Sign In</a>
_END;
}
echo <<<_END
<br>
<h1>2CWK50: A Survey Website</h1>
_END;
?>