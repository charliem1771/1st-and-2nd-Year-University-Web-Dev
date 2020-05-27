6G5Z2107 - 2CWK50 - 2018/19
<Charlie Moorcroft>
<17090875>


SETUP:
To setup the site first run create_data.php, afterwards either navigate to sign_up.php to create an account or to sign_in.php to sign in to a existing account
in the database. When signing up to the system you will need a username,password,firstname,lastname,email,telephone number and a date of birth, once all of these 
details are supplied the account can be created. If your signing in with a existing account simply input the username and password and as long as they have a valid
match in the database it'll sign you in. 


DOCUMENTATION:

--------------
User Accounts
--------------

The user accounts system consists of 6 pages and 7 files the pages are used to manage,create and sign in/out of the user accounts. The script sign_up.php and sign_in.php is already explained 
in a good amount of detail in the setup section of this document. The sign_out.php file does exactly what the name says and signs out the user account it does this by destroying the 
login session. The next important file is the account.php page this displays details of the account the user is logged into and allows them to be changed when the user wants. It does 
this by having a simple form which when submitted querys the SQL database to update the database with the new values input in the form. The next file is admin.php this file doesn't 
directly affect the user accounts in any way it does allow access to the file adminUsers.php which allows the user accounts to be managed. In the adminUsers.php file it allows for the
creation,editing and deletion of user accounts along with being able to display all of the current user accounts, a indepth explanation of how this system works is detailed in the
comments of adminUser.php. The 7th file is the helper.php file which is used to validate all inputted,string,dates,emails and dates for the user accounts and all data added to the 
database.

-------------------
Design and Analysis
-------------------

This section has no real programming functionality it is simply a page documenting the analysis of 3 different survey sites styled with HTML it breaks down the 3 sites for analysis.
It does this by breaking down the following key components of each site the layout,ease of use,acccount login and set up,question types,analysis tool. The 3 sites I selected are broken
down in full detail in the page competitors.php.

-----------------
SurveyManagement
-----------------

The survey management consistes of 3 pages and 4 files the main file is staticsurvey.php, this provides a basic static survey for the user to fill out it asks the user questions using
text forms,date form,radio buttons and check boxes. Much like account.php or sign_up.php it takes the data from the form which contains the new data and inserts it into the database
it does this in almost the same way as sign_up.php with a few key differences all of this is explained in detail in the comments of static survey.php. Netx up is adminSurveys.php
this file is almost identical in the code and in functionality with a few key differneces to adminUsers.php to understand how this file works read the comments of adminUsers.php and then 
read through adminSurveys.php and you should understand everything. The next file is admin.php which is used again for navigation but this time too userSurveys.php,once again the 
helper.php which once again is used for validation of all inputted values from staticsurvey.php. 

--------------
Survey Results
--------------

The survey results are controlled with one file display_results.php this file grabs all of the survey responses and puts them into a table it also interacts with javascript apis
to create a series of charts like bar charts and pie charts. There is also a slider which controls the number of values displayed by the pie chart it can be alerted according to 
number of responses. Unlike every other script display_results.php does not need to interact with any external scripts apart form the database. 

-----------
Other files
-----------

This covers what the other php files do in the system.

The create_data.php file is used to create the database and fill it with dummy data it does this using php and SQL commands the details of exactly how this is done are in
the comments of the file.

Header.php is a simple HTML navigation file allwoing the user to go to most of the pages on the site.

Admin.php is another HTML naivgation file which allows users with the username admin to navigate to the pages with the php files adminUsers.php and adminSurveys.php.

Helper.php uses PHP validation and sanitisation functions to check all data inputted through the forms 