<?php
require_once "header.php";
require_once "credentials.php";
$showUsers = "username"; 
$username = "";
$password = "";
//variables to hold validation errors 
$username_errors = ""; 
$password_errors = "";
$firstname_errors = "";
$lastname_errors = "";
$email_errors = ""; 
$DOB_errors = ""; 
$telenum_errors = ""; 
$errors = "";
$message = "";
$showUsers = "username";
$showUsers = true;
//checking if the user is logged in there username is admin
    if (isset($_SESSION['loggedInSkeleton']) && ($_SESSION['username']=='admin')) 
    {
        //checking if showUsers is available
        if (isset($_GET['showUsers'])) 
        {
            //if show users is available get it
            $showUsers = $_GET['showUsers'];
        }
        else if (isset($_GET['op']) && (isset($_GET['id'])))  //checking if id and op are available for grabbing
        {
            //getting the delete operation
            if ($_GET['op']=="delete") 
            {
                //calling the delete function and passing the id of the selected user
                deleteUsers($dbhost, $dbuser, $dbpass, $dbname, $_GET['id']);
                $showUsers = true;
            }
            else if ($_GET['op']=="newUser" || $_GET['op']=="editUser") //seeing if the user wants to get the edit or new operations
            {
                $showUsers = false; //setting showUsers to false so only the add and edit forms are visible

                if ($_GET['op']=="newUser") 
                {
                    //if the operation is new do what happens below
                    $action="Add New"; //setting acction to the text add new
                    $usernameAction = "";
                    //note db action is used a variable to put text on the button depending on operation picked
                    $dbAction = "newUser";//setting dbaction to newUser
                    $editRow['username'] = ""; //creating a array of empty variables
                    $editRow['password'] = ""; $editRow['firstname'] = $editRow['surname'] = $editRow['DOB'] = $editRow['email'] = $editRow['telenum'] = "";
                }
                else 
                {
                    $action="Edit"; //setting the action to edit
                    $dbAction = "changeUser"; //setting db action to changeuser
                    $editRow = editUsers($dbhost, $dbuser, $dbpass, $dbname, $_GET['id']); //passing values to the editUsers function
                }

                echo <<<_END
                <!--This a form used to submit data for the edit and create new functions much like the form in account.php this form has validation and can
                grab values from the database to populate its fields if the user wants -->
                <form action="adminUsers.php" method="post" enctype="multipart/form-data">
                    <h2>$action User</h2>
                    <table>
                    <tr>
                        <th>Username</th>
                        <td><input size="30" type="text" minlength="1" maxlength="32" name="username" value="{$editRow['username']}" required></td>$username_errors
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td><input size="30" type="password" minlength="6" maxlength="40" name="password" value = "{$editRow['password']}" required></td>$password_errors
                    </tr>
                    <tr>
                        <th>First Name</th>
                        <td><input size="30" type="text" minlength="1" maxlength="32" name="firstname" value="{$editRow['firstname']}" required></td>$firstname_errors
                    </tr>
                    <tr>
                        <th>Last Name</th>
                        <td><input size="30" type="text" minlength="1" maxlength="64" name="surname" value="{$editRow['surname']}" required></td>$lastname_errors
                    </tr>
                    <tr>
                        <th>DOB<th/>
                        <td><input name="DOB" type="text" value="{$editRow['DOB']}" required></td>$DOB_errors
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><input size="30" type="email" name="email" value="{$editRow['email']}" required></td>$email_errors
                    </tr>
                    <tr>
                        <th>Telephone</th>
                        <td><input size="30" type="text" minlength="1" maxlength="25" name="telenum" value="{$editRow['telenum']}"></td>$telenum_errors
                    </tr>
                    </table>
                        <input type="submit" name="$dbAction" value="$action User">
                </form>
_END;

                }

            }
        elseif (isset($_POST['changeUser']) || (isset($_POST['newUser']))) 
        {
            //if the changeUser or new user value was selected for the input form and posted do this

            //posting all values from the input form
            $username = $_POST['username'];
            $password = $_POST['password'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['surname'];
            $DOB = $_POST['DOB'];
            $email = $_POST['email'];
            $telenum = $_POST['telenum'];

            
            $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            
            if (!$connection) 
            {
                die("Connection failed: " . $mysqli_connect_error);
            }
            //cleaning all of the posted variables
            $username = sanitise($username, $connection);
            $password = sanitise($password, $connection);
            $firstname = sanitise($firstname, $connection);
            $lastname = sanitise($lastname, $connection);
            $DOB = sanitise($DOB, $connection);
            $email = sanitise($email, $connection);
            $telenum = sanitise($telenum, $connection);
            
            //making sure all the variables have a valid input
            $username_errors = validateString($username, 1, 16);
            $password_errors = validateString($password, 1, 16);
            $firstname_errors = validateString($firstname, 1, 24);
            $lastname_errors = validateString($lastname, 1, 24);
            $telenum_errors = validateString($telenum, 1, 24);
            $email_errors = validateEmail($email);
            $DOB_errors = validateDate($DOB);
        
            $errors = $username_errors . $password_errors . $firstname_errors . $lastname_errors . $telenum_errors . $email_errors . $DOB_errors;

            if ($errors == "") 
            {
                if (isset($_POST['newUser'])) 
                {
                    //if newUser has been posted by the form then prefrom sql statement to insert all of the variables from the form
                    $sql = "INSERT INTO users (username, password, firstname, surname, email, telenum,DOB)
                    VALUES ('$username', '$password', '$firstname', '$lastname', '$email', '$telenum', '$DOB')";
                    
                    if (mysqli_query($connection, $sql)) 
                    {
                        //if the query worked print a messgae saying so
                        echo "Database updated new user $username added";
                        //show the users table again
                        $showUsers = true;
                    }
                    else 
                    {
                        echo "Error inserting row";
                    }
                }
                else 
                {
                    //if the post wasn't newUsser its editUser
                    //Call a update to set any data that has been changed
                    $sql = "UPDATE users SET username='$username', password='$password', firstname='$firstname', surname='$lastname', DOB='$DOB', email='$email', telenum = '$telenum'
                    WHERE username='$username'";

                    if (mysqli_query($connection, $sql)) 
                    {
                        //making sure its worked if so echo the message below
                        echo "User $username has been edited";
                        $showUsers = true;
                    }
                    else 
                    {
                        die("Error updating row: " . mysqli_error($connection));
                    }

                }

            }
            mysqli_close($connection);
        }

        if($showUsers)
        {
            //passing all tne variables to show users 
            showUsers($dbhost,$dbuser,$dbpass,$dbname,$showUsers);
        }
    }
    else 
    {
        echo "Sorry, you must be an administrator to access this resource";
    }

//The Delete Function
function deleteUsers ($dbhost, $dbuser, $dbpass, $dbname, $userField) 
{

    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    if (!$connection)
    {
        die("Connection failed: " . $mysqli_connect_error);
    }

    mysqli_select_db($connection, $dbname);
    //deleting users with a matching ussername to the one picked
    $query = "DELETE FROM users WHERE username='$userField'";
    $result = mysqli_query($connection, $query);
    if ($result) 
    {
        echo "Successfuly deleted user account";
    }
    mysqli_close($connection);
}

//The edit users function
function editUsers ($dbhost, $dbuser, $dbpass, $dbname, $userField) 
{

    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    
    if (!$connection)
    {
        die("Connection failed: " . $mysqli_connect_error);
    }

    mysqli_select_db($connection, $dbname);
    //selecting all of the user details
    $query = "SELECT * FROM users WHERE username='$userField'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);

    mysqli_close($connection);
    
    return $row;
}
//The function used to show all of users
function showUsers($dbhost, $dbuser, $dbpass, $dbname, $userField) 
{

    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if (!$connection)
    {
        die("Connection failed: " . $mysqli_connect_error);
    }

    mysqli_select_db($connection, $dbname);
    //grabbing all the users from the database and ordering them by the userField
    $query = "SELECT * FROM users ORDER BY $userField";
    
    $result = mysqli_query($connection, $query);
    
    $n = mysqli_num_rows($result);

     echo "<h2>Manage Accounts</h2><table>";
     //As you can see the showUsers is embedded in the href for use across the script
     echo "<tr><th><a href='adminUsers.php?showUsers=username'>Username</a></th>
            <th><a href='adminUsers.php?showUsers=firstname'>First Name</a></th>
            <th><a href='adminUsers.php?showUsers=lastname'>Last Name</a></th>";
    if ($n>0) 
    {
        //using a for loop to iterate through all of the rows
        for ($i = 0; $i < $n; $i++) 
        {
            //grabbing the results of the query and putting them in the associated variable
            $row = mysqli_fetch_assoc($result);
            /*Putting all of the surnames and firstnames from the database into the tables, notice how the editUser and delete operations are embedded
            in the table. Along with the id op this allows us to grab this information above in the script and use it to edit and delete users.*/
            echo <<<_END
                <tr>
                <td><a href="adminUsers.php?op=editUser&id={$row['username']}">{$row['username']}</a></td>
                <td>{$row['firstname']}</td>
                <td>{$row['surname']}</td>
                <td><a href="adminUsers.php?op=delete&id={$row['username']}">Delete</a></td>
                </tr>
_END;
        }

        // complete formatting
        //The new operation is embedded in this table and like the others is used above to help create a new user
        echo "<tr><td><a href='adminUsers.php?op=newUser&id=newUser'>Add New Survey</a></td></tr>";
        echo "</table>";

    }
    else 
    {
        echo "No information found in users table";
    }
     // we're finished with the database, close the connection:
     mysqli_close($connection);
}

// finish of the HTML for this page:
require_once "footer.php";

?>