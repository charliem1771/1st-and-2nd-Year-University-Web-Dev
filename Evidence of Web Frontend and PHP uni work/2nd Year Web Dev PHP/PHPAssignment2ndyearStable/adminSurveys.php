<?php
require_once "header.php";

require_once "credentials.php";

$username = "";
//Variables to hold all validation errors
$username_errors = ""; 
$resp1_errors = "";
$resp2_errors = "";
$resp3_errors = "";
$resp4_errors = ""; 
$resp5_errors = ""; 
$resp6_errors = ""; 
$resp7_errors = ""; 
$resp8_errors = ""; 
$errors = "";
$message = "";
$showResps = "username";
$showResps = true;
//making sure the user is logged and the username is admin
    if (isset($_SESSION['loggedInSkeleton']) && ($_SESSION['username']=='admin')) 
    {
        //calling show survey reponses
        if (isset($_GET['showResps'])) 
        {
            $showResps = $_GET['showResps'];
        }
        //Everything from here to line 101 is almost the exact same as it is in the adminUsers script by reading that you should be able to understand this
        else if (isset($_GET['op']) && (isset($_GET['id']))) 
        {
    
            if ($_GET['op']=="delete") 
            {
                deleteResps($dbhost, $dbuser, $dbpass, $dbname, $_GET['id']);
                $showResps = true;
            }
            else if ($_GET['op']=="newResp" || $_GET['op']=="editResps")
            {
                $showResps = false;

                if ($_GET['op']=="newResp") 
                {
                    $action="Add New";
                    $usernameAction = "";
                    $dbAction = "newResp";
                    $editRow['username'] = "";
                    $editRow['resp1'] = ""; $editRow['resp2'] = $editRow['resp3'] = $editRow['resp4'] = $editRow['resp5'] = $editRow['resp6'] = $editRow['resp7'] = $editRow['resp8'] =  "";
                }
                else 
                {
                    $action="Edit";
                    $dbAction = "changeResp";
                    $editRow = editResps($dbhost, $dbuser, $dbpass, $dbname, $_GET['id']);
                }

                echo <<<_END
                <form action="adminSurveys.php" method="post" enctype="multipart/form-data">
                    <h2>$action User</h2>
                    <table>
                    <tr>
                        <th>Username</th>
                        <td><input size="30" type="text" minlength="1" maxlength="32" name="username" value="{$editRow['username']}"required></td>$username_errors
                    </tr>
                    <tr>
                        <th>Response 1</th>
                        <td><input size="30" type="text" minlength="1" maxlength="40" name="resp1" value = "{$editRow['resp1']}" required></td>$resp1_errors
                    </tr>
                    <tr>
                        <th>Response 2</th>
                        <td><input size="30" type="text" minlength="1" maxlength="32" name="resp2" value="{$editRow['resp2']}" required></td>$resp2_errors
                    </tr>
                    <tr>
                        <th>Response 3</th>
                        <td><input type="text"  name="resp3" value="1"></td>
                    </tr>
                    <tr>
                        <th>Response 4<th/>
                        <td><input type="text"  name="resp4" value="1" ></td>
                    </tr>
                    <tr>
                        <th>Response 5</th>
                        <td><input type="text" name="resp5" value="{$editRow['resp5']}" required></td>$resp5_errors
                    </tr>
                    <tr>
                        <th>Response 6</th>
                        <td><input size="30" type="text" minlength="1" maxlength="25" name="resp6" value="{$editRow['resp6']}" required></td>$resp6_errors
                    </tr>
                     <tr>
                        <th>Response 7</th>
                        <td><input type="text" name="resp7" value="1"></td>
                    </tr>
                    <tr>
                        <th>Response 8</th>
                        <td><input type="text" name="resp8" value="1"></td>
                    </tr>
                    </table>
                        <input type="submit" name="$dbAction" value="$action User">
                </form>
_END;

                }

        }
        elseif (isset($_POST['changeResp']) || (isset($_POST['newResp']))) 
        {
        //setting the reponses from the checkboxes to true if required
        if (isset($_POST['resp4'])) 
        {
            $resp3 = true;
        }
        else 
        {
            $resp3 = false;
        }

        if (isset($_POST['resp5'])) 
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
        //posting all of the details that arn't booleans
            $username = $_POST['username'];
            $resp1 = $_POST['resp1'];
            $resp2 = $_POST['resp2'];
            $resp5 = $_POST['resp5'];
            $resp6 = $_POST['resp6'];
            
            //standard connection check along with validation and sanitisation code
            $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            
            if (!$connection) 
            {
                die("Connection failed: " . $mysqli_connect_error);
            }

            $username = sanitise($username, $connection);
            $resp1 = sanitise($resp1, $connection);
            $resp2 = sanitise($resp2, $connection);
            $resp3 = sanitise($resp3, $connection);
            $resp4 = sanitise($resp4, $connection);
            $resp5 = sanitise($resp5, $connection);
            $resp6 = sanitise($resp6, $connection);
            $resp7 = sanitise($resp7, $connection);
            $resp8 = sanitise($resp8, $connection);


            $username_errors = validateString($username, 1, 32);
            $resp1_errors = validateString($resp1, 6, 40);
            $resp2_errors = validateString($resp2, 1, 32);
            $resp5_errors = validateDate($resp5);
            $resp6_errors = validateString($resp6,0,16);

            $errors = $username_errors . $resp1_errors . $resp2_errors . $resp3_errors . $resp4_errors . $resp5_errors . $resp6_errors. $resp7_errors . $resp8_errors;

            if ($errors == "") 
            {
    
                if (isset($_POST['newResp'])) 
                {
                    //if new resp is called insert new data into the staticsurvey table
                    $sql = "INSERT INTO staticsurvey (username, resp1, resp2, resp3, resp4, resp5,resp6,resp7,resp8)
                    VALUES ('$username', '$resp1', '$resp2', '$resp3', '$resp4', '$resp5', '$resp6','$resp7','$resp8')";
                    
                    //checking if it worked
                    if (mysqli_query($connection, $sql)) 
                    {
                        echo "Database updated new resp $username added";
                        $showResps = true;
                    }
                    else 
                    {
                        die("Error inserting row: " . mysqli_error($connection));
                    }
                }   
                else 
                {
                    //if where editing a user set the values to those inputted in the form
                    $sql = "UPDATE staticsurvey SET username='$username', resp1='$resp1', resp2 ='$resp2', resp3 ='$resp3', resp4 ='$resp4', resp5 ='$resp5', resp6 = '$resp6', resp7 ='$resp7', resp8 = '$resp8'
                    WHERE username='$username'";
                    //checking it worked
                    if (mysqli_query($connection, $sql)) 
                    {

                        echo "Resp $username has been edited";
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

        if($showResps)
        {
            showResps($dbhost,$dbuser,$dbpass,$dbname,$showResps);
        }
    }
    else 
    {
        echo "Sorry, you must be an administrator to access this resource";
    }
//This function is almost identical to the one explained in adminUsers.php
function deleteResps ($dbhost, $dbuser, $dbpass, $dbname, $userField) 
{

    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    if (!$connection)
    {
        die("Connection failed: " . $mysqli_connect_error);
    }

    mysqli_select_db($connection, $dbname);
    $query = "DELETE FROM staticsurvey WHERE username='$userField'";
    $result = mysqli_query($connection, $query);
    if ($result) 
    {
        echo "Successfuly deleted user account";
    }
    mysqli_close($connection);
}
//This function is almost identical to the one explained in adminUsers.php
function editResps ($dbhost, $dbuser, $dbpass, $dbname, $userField) 
{

    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    
    if (!$connection)
    {
        die("Connection failed: " . $mysqli_connect_error);
    }

    mysqli_select_db($connection, $dbname);

    $query = "SELECT * FROM staticsurvey WHERE username='$userField'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);

    mysqli_close($connection);
    
    return $row;
}

//function to show responses
function showResps($dbhost, $dbuser, $dbpass, $dbname, $userField) 
{
    //boiler plate connection code
    $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if (!$connection)
    {
        die("Connection failed: " . $mysqli_connect_error);
    }

    mysqli_select_db($connection, $dbname);
    
    //ordering reponses by the usernames
    $query = "SELECT * FROM staticsurvey ORDER BY $userField";
    
    $result = mysqli_query($connection, $query);
    
    $n = mysqli_num_rows($result);
    echo "<h2>Manage Surveys</h2><table>";
    //echong out all the usernames
    echo "<tr><th><a href='adminSurveys.php?showResps=username'>Username</a></th>";
    if ($n>0) 
    {
        for ($i = 0; $i < $n; $i++) 
        {
            $row = mysqli_fetch_assoc($result);
            /*when the rows are fetched and printed they contain the same operations as they do in adminusers.php yet they don't grab the first and lastnames since
            it's not necessary*/
            echo <<<_END
                <tr>
                <td><a href="adminSurveys.php?op=editResps&id={$row['username']}">{$row['username']}</a></td>
                <td><a href="adminSurveys.php?op=delete&id={$row['username']}">Delete</a></td>
                </tr>
_END;
        }
        //Printing out the add new users option
        echo "<tr><td><a href='adminSurveys.php?op=newResp&id=newResp'>Add New User</a></td></tr>";
        echo "</table>";
    }
    else 
    {
        echo "No information found in survey table";
    }
     // we're finished with the database, close the connection:
     mysqli_close($connection);
}
// finish of the HTML for this page:
require_once "footer.php";
?>