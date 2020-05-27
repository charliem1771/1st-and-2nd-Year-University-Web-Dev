<?php

require_once "credentials.php";


//boiler plate connection code
$connection = mysqli_connect($dbhost, $dbuser, $dbpass);


if (!$connection)
{
	die("Connection failed: " . $mysqli_connect_error);
}
  
//Create a database if no DB exists
$sql = "CREATE DATABASE IF NOT EXISTS " . $dbname;

//Simple if else statement to make sure the database creation worked
if (mysqli_query($connection, $sql)) 
{
	echo "Database created successfully, or already exists<br>";
} 
else
{
	die("Error creating database: " . mysqli_error($connection));
}

//connecting to the newly created database
mysqli_select_db($connection, $dbname);

//Creating users table

//Drop a old version of user table
$sql = "DROP TABLE IF EXISTS users";

//Making sure we dropped the old version of the users table
if (mysqli_query($connection, $sql)) 
{
	echo "Dropped existing table: users<br>";
} 
else 
{	
	die("Error checking for existing table: " . mysqli_error($connection));
}

//Creat the users table and populate it with rows in which records can be inserted
$sql = "CREATE TABLE users (username VARCHAR(16), password VARCHAR(16), email VARCHAR(64),DOB VARCHAR(16),telenum VARCHAR(24),firstname VARCHAR(24),surname VARCHAR(24),PRIMARY KEY(username))";

//Checking if the database was created
if (mysqli_query($connection, $sql)) 
{
	echo "Table created successfully: users<br>";
}
else 
{
	die("Error creating table: " . mysqli_error($connection));
}

//Below is a series of arrays used to store dummy data which will be inserted into the table
$usernames[] = 'barryg'; $passwords[] = 'letmeing'; $emails[] = 'barryg@m-domain.com'; $DOBS[] = '11/10/97'; $telenums[] = '0151 927 8773'; $firstnames[] = 'Barry'; $surnames[] = 'Allen';
$usernames[] = 'mrtest'; $passwords[] = 'test'; $emails[] = 'mr@test.com'; $DOBS[] = '1/11/89'; $telenums[] = '0191 923 7373'; $firstnames[] = 'Mr'; $surnames[] = 'Test';
$usernames[] = 'barrys'; $passwords[] = 'bang'; $emails[] = 'barry@scott.com'; $DOBS[] = '10/11/99'; $telenums[] = '0172 823 8873'; $firstnames[] = 'Barry'; $surnames[] = 'Scott';
$usernames[] = 'admin'; $passwords[] = 'secret'; $emails[] = 'admin@admin.com'; $DOBS[] = '10/11/83'; $telenums[] = '0192 726 8976'; $firstnames[] = 'The'; $surnames[] = 'Admin';
$usernames[] = 'philk'; $passwords[] = 'kane12'; $emails[] = 'phil@kane.com'; $DOBS[] = '15/9/86'; $telenums[] = '0191 727 8896'; $firstnames[] = 'Phil'; $surnames[] = 'Kane';

//Looping through the above arrays and inserting them into the table
for ($i=0; $i<count($usernames); $i++)
{
	//The SQL statement to insert all user data into the table
	$sql = "INSERT INTO users (username, password, email,DOB,telenum,firstname,surname) VALUES ('$usernames[$i]', '$passwords[$i]', '$emails[$i]','$DOBS[$i]','$telenums[$i]','$firstnames[$i]','$surnames[$i]')";
	
	//Checking if the data was inserted into the table
	if (mysqli_query($connection, $sql)) 
	{
		echo "row inserted<br>";
	}
	else 
	{
		die("Error inserting row: " . mysqli_error($connection));
	}
}

//code for staticsurvey table

//dropping staticsturvey if it alreday exists
$sql = "DROP TABLE IF EXISTS staticsurvey";

if(mysqli_query($connection,$sql))
{
    echo "Dropped existing table: staticsurvey<br>";
}
else
{
    die("Error checking  for existing table: " . mysqli_error($connection));
}
//creating the static survey table and all of its rows
$sql = "CREATE TABLE staticsurvey (username VARCHAR(16),resp1 VARCHAR(32),resp2 VARCHAR(32),resp3 BOOLEAN,resp4 BOOLEAN,resp5 VARCHAR(32),resp6 VARCHAR(32),resp7 BOOLEAN,resp8 BOOLEAN)";
if(mysqli_query($connection,$sql))
{
    echo "Table created successfully: staticsurvey<br>";
}
else
{
    die("Error creating table: " . mysqli_error($connection));
}
//creating arrays to hold all of the dummy data 
$usernamesResp[] = 'barryg'; $resp1s[] = 'red'; $resp2s[] = 'I am tall'; $resp3s[] = false; $resp4s[] = true; $resp5s[] = '11-10-1989'; $resp6s[] = 'Bear'; $resp7s[] = false; $resp8s[] = true;
$usernamesResp[] = 'barrys'; $resp1s[] = 'blue'; $resp2s[] = 'I am short'; $resp3s[] = true; $resp4s[] = false; $resp5s[] = '01-09-1965'; $resp6s[] = 'Dog'; $resp7s[] = true; $resp8s[] = false;
$usernamesResp[] = 'philk'; $resp1s[] = 'green'; $resp2s[] = 'I study computer sciene'; $resp3s[] = false; $resp4s[] = true; $resp5s[] = '19-08-1995'; $resp6s[] = 'Cat'; $resp7s[] = false; $resp8s[] = true;
$usernamesResp[] = 'mrtest'; $resp1s[] = 'yellow'; $resp2s[] = 'I like mountains'; $resp3s[] = true; $resp4s[] = false; $resp5s[] = '18-05-2000'; $resp6s[] = 'Seacucumber'; $resp7s[] = true; $resp8s[] = false;

// loop through the arrays above and add rows to the table:
for ($i=0; $i<count($usernamesResp); $i++)
{
	$sql = "INSERT INTO staticsurvey (username, resp1, resp2,resp3,resp4,resp5,resp6,resp7,resp8) VALUES ('$usernamesResp[$i]', '$resp1s[$i]', '$resp2s[$i]','$resp3s[$i]','$resp4s[$i]','$resp5s[$i]','$resp6s[$i]','$resp7s[$i]','$resp8s[$i]')";
	
	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
		echo "row inserted<br>";
	}
	else 
	{
		die("Error inserting row: " . mysqli_error($connection));
	}
}

// we're finished, close the connection:
mysqli_close($connection);
?>