<?php
require_once "header.php";
//simple HTML naviagtion page for the admin which allows the admin to navigate to the control panels
if (isset($_SESSION['loggedInSkeleton']) && ($_SESSION['username']=='admin')) 
{
    echo <<<_END
    <h2>Admin Control Page</h2>
        <table>
            <tr>
                <th>User Control</th>
                <th>Survey Control</th>
            </tr>
            <tr>
                <td><a href="adminUsers.php">User Control</a></td>
                <td><a href="adminSurveys.php">Survey Control</a></td>
            </tr>
        </table>
_END;
}
else
{
    echo "Not admin";
}
require_once "footer.php";
?>