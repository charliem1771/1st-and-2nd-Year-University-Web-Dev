<?php


require_once "header.php";

if (!isset($_SESSION['loggedInSkeleton']))
{
	//users not logged in show this
	echo "You must be logged in to view this page.<br>";
}
else
{
	//user clicked log out clear sessionarray
	$_SESSION = array();
	//clear the session cookie
	setcookie(session_name(), "", time() - 2592000, '/');
	//destroy session data on the server side
	session_destroy();

	echo "You have successfully logged out, please <a href='sign_in.php'>click here</a><br>";
}

// finish of the HTML for this page:
require_once "footer.php";

?>